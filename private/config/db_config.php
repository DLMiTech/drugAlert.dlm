<?php
/** database config **/
const DB_HOST = "localhost";

if($_SERVER['SERVER_NAME'] == 'localhost')
{
    define("DB_USER", "root");
    define("DB_PASS", "@DLMiTech1248");
    define("DB_NAME", "drug_alert_db");
}else
{
    define("DB_USER", "userName");
    define("DB_PASS", "M1248@");
    define("DB_NAME", "name");
}