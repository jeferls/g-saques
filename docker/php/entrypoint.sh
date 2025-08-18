#!/usr/bin/env bash
set -e

APP_DIR="${APP_DIR:-/var/www/html/src}"
cd "$APP_DIR"

git config --global --add safe.directory /var/www/html       || true
git config --global --add safe.directory /var/www/html/src   || true

# .env em src/ (procura em src/ e na raiz)
if [[ ! -f .env ]]; then
  echo ".env não encontrado, tentando criar a partir de template..."
  for CAND in ".env.template" ".env.example" "../.env.template" "../.env.example"; do
    if [[ -f "$CAND" ]]; then
      cp -f "$CAND" .env
      echo "Criado .env a partir de: $CAND"
      break
    fi
  done
  [[ -f .env ]] || echo "Aviso: nenhum template .env encontrado; seguindo sem criar."
fi

# Composer: garante permissões e instala se necessário
export COMPOSER_ALLOW_SUPERUSER=1
mkdir -p vendor storage/logs bootstrap/cache

chown -R www-data:www-data vendor storage bootstrap/cache || true
chmod -R 775 vendor storage bootstrap/cache || true

if [[ ! -d vendor || -z "$(ls -A vendor 2>/dev/null)" ]]; then
  if [[ -d /vendor_cache && -n "$(ls -A /vendor_cache 2>/dev/null)" ]]; then
    echo "Recuperando dependências pré-instaladas de /vendor_cache..."
    cp -a /vendor_cache/. vendor/
  else
    echo "Instalando dependências do Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
  fi
fi

# Garante arquivo de log e permissões (com fallback pra 777 em bind-mount)
: > storage/logs/laravel.log || true
if [[ ! -w storage/logs/laravel.log ]]; then
  echo "Sem permissão em storage/logs; aplicando 777 (dev)."
  chmod -R 777 storage bootstrap/cache || true
fi

php artisan storage:link >/dev/null 2>&1 || true

echo "Iniciando aplicação..."
exec "$@"
