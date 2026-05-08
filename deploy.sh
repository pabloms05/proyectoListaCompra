#!/bin/bash

# Script de despliegue para Lista Compra en VPS

# Detectar si usa docker-compose o docker compose
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null; then
    DOCKER_COMPOSE="docker compose"
else
    echo "❌ Error: Docker Compose no está instalado"
    exit 1
fi

echo "🚀 Iniciando despliegue de Lista Compra..."

# Colores para mensajes
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Detener si hay algún error
set -e

# 0. Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    echo -e "${RED}❌ Error: No se encuentra docker-compose.yml${NC}"
    echo -e "${RED}   Asegúrate de estar en /home/Proyecto/ListaCompra${NC}"
    exit 1
fi

# 1. Copiar archivo .env
echo -e "${YELLOW}📝 Configurando variables de entorno...${NC}"
if [ ! -f .env ]; then
    if [ ! -f .env.production ]; then
        echo -e "${RED}❌ Error: No se encuentra .env.production${NC}"
        echo -e "${RED}   Este archivo es crítico y debe estar en el proyecto${NC}"
        exit 1
    fi
    cp .env.production .env
    echo -e "${GREEN}✅ Archivo .env creado${NC}"
else
    echo -e "${YELLOW}⚠️  .env ya existe, usando existente${NC}"
fi

# 2. Generar APP_KEY si no existe
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}🔑 Generando APP_KEY...${NC}"
    $DOCKER_COMPOSE run --rm app php artisan key:generate
    echo -e "${GREEN}✅ APP_KEY generada${NC}"
fi

# 3. Detener contenedores existentes
echo -e "${YELLOW}🛑 Deteniendo contenedores existentes...${NC}"
$DOCKER_COMPOSE down || true

# 4. Construir imágenes
echo -e "${YELLOW}🔨 Construyendo imágenes Docker...${NC}"
$DOCKER_COMPOSE build --no-cache

# 5. Iniciar contenedores
echo -e "${YELLOW}🚢 Iniciando contenedores...${NC}"
$DOCKER_COMPOSE up -d

# 6. Crear archivo de base de datos SQLite si no existe
echo -e "${YELLOW}📁 Configurando base de datos SQLite...${NC}"
$DOCKER_COMPOSE exec -T app touch /var/www/html/database/database.sqlite
$DOCKER_COMPOSE exec -T app chmod 664 /var/www/html/database/database.sqlite
$DOCKER_COMPOSE exec -T app chown www-data:www-data /var/www/html/database/database.sqlite

# 7. Ejecutar migraciones
echo -e "${YELLOW}📊 Ejecutando migraciones de base de datos...${NC}"
$DOCKER_COMPOSE exec -T app php artisan migrate --force

# 8. Ejecutar seeders (datos iniciales de categorías y productos)
echo -e "${YELLOW}🌱 Ejecutando seeders...${NC}"
$DOCKER_COMPOSE exec -T app php artisan db:seed --force

# 9. Instalar dependencias de Node.js para compilar assets
echo -e "${YELLOW}📦 Instalando dependencias de Node.js...${NC}"
$DOCKER_COMPOSE exec -T app npm install --unsafe-perm

# 10. Compilar assets de Vite (CSS y JS)
echo -e "${YELLOW}🎨 Compilando assets frontend (Tailwind CSS + Vite)...${NC}"
$DOCKER_COMPOSE exec -T app node /var/www/html/node_modules/vite/bin/vite.js build

# 11. Establecer permisos en archivos compilados
echo -e "${YELLOW}🔒 Estableciendo permisos en assets...${NC}"
$DOCKER_COMPOSE exec -T app chmod -R 755 /var/www/html/public/build/

# 12. Eliminar archivo public/hot si existe (prevenir referencias a Vite dev server)
echo -e "${YELLOW}🧹 Eliminando referencias a servidor de desarrollo...${NC}"
$DOCKER_COMPOSE exec -T app rm -f /var/www/html/public/hot || true

# 13. Limpiar caché
echo -e "${YELLOW}🧹 Limpiando caché de Laravel...${NC}"
$DOCKER_COMPOSE exec -T app php artisan optimize:clear

# 14. Optimizar para producción
echo -e "${YELLOW}⚡ Optimizando para producción...${NC}"
$DOCKER_COMPOSE exec -T app php artisan config:cache
$DOCKER_COMPOSE exec -T app php artisan route:cache
$DOCKER_COMPOSE exec -T app php artisan view:cache

# 15. Establecer permisos finales
echo -e "${YELLOW}🔒 Estableciendo permisos finales...${NC}"
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data /var/www/html/storage
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data /var/www/html/public/build

# 16. Mostrar estado
echo -e "${YELLOW}📋 Estado de los contenedores:${NC}"
$DOCKER_COMPOSE ps

echo ""
echo -e "${GREEN}✅ ¡Despliegue completado con éxito!${NC}"
echo -e "${GREEN}🌐 La aplicación está disponible en: http://listacompra.duckdns.org${NC}"
echo ""
echo -e "${GREEN}✨ Características activadas:${NC}"
echo "  ✓ Base de datos SQLite con migraciones completas"
echo "  ✓ Seeders ejecutados (10 categorías, 50 productos con precios)"
echo "  ✓ Sistema de precios integrado (productos y listas con totales)"
echo "  ✓ Diferenciación productos sistema vs usuario (precios editables)"
echo "  ✓ Assets compilados (Tailwind CSS optimizado para escala 125%-150%)"
echo "  ✓ Alpine.js v3.15.0 para reactividad"
echo "  ✓ SweetAlert2 para confirmaciones y modales"
echo "  ✓ Sistema de compartir listas funcional (propietario/editor)"
echo "  ✓ Google OAuth configurado (requiere credenciales en .env)"
echo "  ✓ Políticas de autorización implementadas"
echo ""
echo -e "${YELLOW}📝 Comandos útiles:${NC}"
echo "  Ver logs:              $DOCKER_COMPOSE logs -f"
echo "  Detener:               $DOCKER_COMPOSE down"
echo "  Reiniciar:             $DOCKER_COMPOSE restart"
echo "  Entrar al contenedor:  $DOCKER_COMPOSE exec app bash"
echo "  Recompilar assets:     $DOCKER_COMPOSE exec app node /var/www/html/node_modules/vite/bin/vite.js build"
echo "  Limpiar caché:         $DOCKER_COMPOSE exec app php artisan optimize:clear"
echo ""
echo -e "${YELLOW}⚠️  Recordatorios importantes:${NC}"
echo "  • Asegúrate de que APP_ENV=production en .env"
echo "  • Configura GOOGLE_CLIENT_ID y GOOGLE_CLIENT_SECRET si usas OAuth"
echo "  • Actualiza las URIs autorizadas en Google Cloud Console"
echo "  • Para recargar cambios en el navegador usa Ctrl+Shift+R"
echo "  • Los productos del seeder tienen precio fijo (no editables)"
echo "  • Los productos creados por usuarios pueden editar precio en cualquier momento"
echo ""
echo -e "${GREEN}🎉 Sistema completamente funcional y listo para producción${NC}"
