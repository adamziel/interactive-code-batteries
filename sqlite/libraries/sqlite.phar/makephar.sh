#!/bin/bash

rm -rf build
mkdir build
mkdir -p build/sqlite build/vendor/phpmyadmin/sql-parser/src
cp -r ./source/sqlite-database-integration/wp-includes/sqlite/* ./build/sqlite
cp ./source/* ./build
cp ./*.php ./build

cd build

# Minify PHP files
# for phpfile in $(find ./ -type f -name '*.php' ); do \
#     php -w $phpfile > $phpfile.small && \
#     mv $phpfile.small $phpfile; \
# done

rm sqlite.phar
php -d 'phar.readonly=0' makephar.php && php usage.php && mv sqlite.phar ../
cd ..
rm build.ht.sqlite build.htaccess buildindex.php build/composer*

du -sh build
ls -sgh | grep sqlite.phar