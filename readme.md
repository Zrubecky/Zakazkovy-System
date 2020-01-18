# Testovací Zadání: Zakázkový Systém

Zakázkový systém vytvořený jako cvičení frameworku Nette. Aplikace obsahuje login systém a systém pro zadávání a přehled zakázek do firmy zabývající se vývojem webových stránek a aplikací.

Uživatel se může do systému zaregistrovat, měnit/resetovat heslo, zadávat zakázky a uploadovat přílohy. Úvodní obrazovka obsahuje přehled zakázek.

## Instalace

Po stažení apliakce je nutné nainstalovat framework Nette 3.0 pomocí:

```
composer install
```

### Databáze

Dále je nutné vytvořit si databázi. Databáze je připravena ve složce:

```
database/
```

v kořenovém adresáři.

Soubor **create_database.sql** obsahuje sql příkaz pro vytvoření databáze s názvem **bm_zakazkovy_system**, jenž je předkonfigurována v souboru:

```
app/config/config.neon
```

Tabulky jsou připravené v souboru **create_tables.sql** i s několika předpřipravenými typy zakázek.

### Konfigurace

V konfiguračním souboru config.neon je předpřipravené nastavení emailu:

```
mailer:
   smtp: true
   host: 
   port: 465
   username: 
   password: 
   secure: ssl
```

Pro správný chod aplikace je nutné mailer dokonfigurovat.

Jako poslední krok je nutné vytvořit lokální konfigurační **config.local.neon** (možné i lokálně nastavit mailer) ve složce:

```
app/config/
```

Nebo lokální konfigurační soubor odstranit z nastavení v souboru:

```
app/bootstrap.php
```

Poté je aplikace plně funkční.

## Vytvořeno pomocí

* [Nette](https://nette.org) - PHP framework
* [jQuery](https://jquery.com) - Javascript framework
* [Bootstrap](https://getbootstrap.com) - CSS framework
* [Bootstrap-datepicker](https://bootstrap-datepicker.readthedocs.io/en/latest/) - Front end kalendář
* [Krajee Bootstrap File Input](https://plugins.krajee.com/file-input) - Front end drag & drop upload

## Autor

* **Filip Zrubecký** - [Zrubecky](https://github.com/Zrubecky)