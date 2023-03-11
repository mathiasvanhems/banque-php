Composer install
Composer update
npm install
npm run dev 
npm run watch

fix sequence
DO $$
DECLARE 
i TEXT;
BEGIN
    FOR i IN (select table_name from information_schema.tables where table_catalog='banque' and table_schema='public' and table_name!='doctrine_migration_versions') LOOP
    EXECUTE 'Select setval('''||i||'_id_seq'', (SELECT max(id) as a FROM '|| i ||'));';
    END LOOP;
END$$;


Reset sequence
DO $$
DECLARE 
i TEXT;
BEGIN
    FOR i IN (select table_name from information_schema.tables where table_catalog='banque' and table_schema='public' and table_name!='doctrine_migration_versions') LOOP
    EXECUTE 'Select setval('''||i||'_id_seq'', 1);';
    END LOOP;
END$$;