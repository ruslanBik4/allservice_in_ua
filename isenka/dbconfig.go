package main
import (
    "fmt"
    "regexp"
    "database/sql"
    "github.com/go-sql-driver/mysql"
    )

var dbConn *sql.DB

func doConnect() *sql.DB {
    var DriveName mysql.MySQLDriver
    var Error error
    
    
    dbConn, Error = sql.Open( "mysql", "u_allservi:iizJ3KKZ@/allservi?persist" )
    if Error != nil {
            fmt.Println(Error)
//             throw 
            return  nil
    } else if dbConn == nil {
         fmt.Println( DriveName )
        return nil
    }
    
//     doQuery("SET NAMES cp1251")
    
    return dbConn

}

func doQuery( sql string, args ...interface{})  *sql.Rows {
    
    rows, Error :=  dbConn.Query( sql, args ...)

    if Error != nil {
            Result, err := regexp.MatchString( "'phpacademy.category' doesn't exist", Error.Error() )
            if Result {
                rowsAffected, err := dbConn.Exec("create table `category` (  `key_category` int(11) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(255) NOT NULL,  `key_parent` int(11) NOT NULL DEFAULT '-1',  `short_text` mediumtext,  `long_text` longtext,  `text_task` mediumtext,  `reg_expr` varchar(255) NOT NULL COMMENT 'проверочное выражение для задания',  `is_view` int(11) NOT NULL DEFAULT '1',  `video` varchar(255) NOT NULL,  `date_sys` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `leaf` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Признак элемента, завершающего ветку (лист)',  PRIMARY KEY (`key_category`),  KEY `name` (`name`) ) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8" )
                if err != nil {
                    fmt.Sprintf("During create table error = %q", err) 
                    return nil
                }
                 fmt.Sprintf( "#%d %s ", rowsAffected, "Not table category, i create!" )
                return nil
            }        else {
                 fmt.Sprintf("During execute query error = %q", err)
                 return nil
                
            }
    }
    
    return rows
}
}