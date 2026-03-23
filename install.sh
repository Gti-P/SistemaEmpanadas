#!/bin/bash
# =============================================================
# Script de instalación - Empanadas POS
# Para usar en GitHub Codespaces o servidor Ubuntu/Debian
# =============================================================

set -e
echo "========================================"
echo "  🫓 Empanadas POS - Instalación"
echo "========================================"

# 1. Instalar dependencias de Composer
echo ""
echo "📦 Instalando dependencias PHP..."
composer install --no-interaction --optimize-autoloader

# 2. Configurar .env
if [ ! -f .env ]; then
    echo ""
    echo "⚙️  Configurando archivo .env..."
    cp .env.example .env
    php artisan key:generate
else
    echo "✅ .env ya existe"
fi

# 3. Crear permisos de storage
echo ""
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

# 4. Importar base de datos
echo ""
echo "🗄️  Importando base de datos..."
echo "   Asegúrate de haber configurado DB_* en .env"
echo ""
read -p "¿Deseas importar la base de datos ahora? (s/n): " IMPORT_DB
if [ "$IMPORT_DB" = "s" ] || [ "$IMPORT_DB" = "S" ]; then
    read -p "Usuario MySQL (default: root): " DB_USER
    DB_USER=${DB_USER:-root}
    mysql -u "$DB_USER" -p < database/empanadas_pos.sql
    echo "✅ Base de datos importada correctamente"
else
    echo "   Puedes importarla manualmente con:"
    echo "   mysql -u root -p < database/empanadas_pos.sql"
    echo "   O usando migraciones:"
    echo "   php artisan migrate --seed"
fi

# 5. Limpiar cachés
echo ""
echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "========================================"
echo "  ✅ Instalación completada"
echo "========================================"
echo ""
echo "Para iniciar el servidor:"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "Rutas disponibles:"
echo "  /pos    → Punto de Venta"
echo "  /admin  → Administración"
echo ""
