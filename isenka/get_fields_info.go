package main
import (
    "fmt"
    "os"
    "io"
    "encoding/json"
//     "database/sql"
    "bytes"
    )
// получение значений полей для форматирования данных
// получение значений полей для таблицы
func GetColumnsProp(table_name string) string {

        type RecordCategory struct {
    		COLUMN_NAME   string
    		DATA_TYPE string
    		CHARACTER_SET_NAME     string
    		COLUMN_COMMENT string
    		COLUMN_DEFAULT string
    		IS_NULLABLE string
     		TITLE string
            TYPE_INPUT string
            IS_VIEW []uint8
    		
        }
        var row RecordCategory
    var result bytes.Buffer
//     []byte(` [{
//         "Поля таблицы " : table_name }}]`)
        
	rows := doQuery("select IFNULL(F_N.title, ''), IFNULL(F_N.type_input, ''), IFNULL(F_N.is_view, ''), COLUMN_NAME, DATA_TYPE, IFNULL( COLUMN_DEFAULT, ''), IS_NULLABLE, IFNULL(CHARACTER_SET_NAME, ''), IFNULL( C.COLUMN_COMMENT, '') from INFORMATION_SCHEMA.COLUMNS C left join allservi.field_names F_N on (F_N.field_name = C.COLUMN_NAME) where C.TABLE_NAME = ?", table_name);
	
	if(rows == nil) {
    	return "Errors"
	}	
	
    w := io.Writer(&result)
    Encode := json.NewEncoder(w)

    for rows.Next() {
        
        err := rows.Scan( &row.TITLE, &row.TYPE_INPUT, &row.IS_VIEW, &row.COLUMN_NAME, &row.DATA_TYPE, &row.COLUMN_DEFAULT, &row.IS_NULLABLE, &row.CHARACTER_SET_NAME, &row.COLUMN_COMMENT )
 
     	if err != nil {
    		fmt.Println("error:", err)
//     		continue
    	}
       
        err = Encode.Encode(row)
    	if err != nil {
    		fmt.Println("error:", err)
    	}

    }
    
	 return result.String();
}

        type RecordTables struct {
    		TABLE_NAME   string
    		TABLE_TYPE string
     		ENGINE string
            TABLE_COMMENT string    		
        }
        type isGet interface {
            Get(int) string
        }

// func (ns *RecordTables) Scan(value interface{}) error {
// 
//   ns.TABLE_NAME = string(value);
//   return nil //sql.convertAssign(&ns.TABLE_NAME, value)
// }

func (ns *RecordTables) Get(i int) string {
    
    switch i  {
    case 0: return ns.TABLE_NAME
    case 1: return ns.TABLE_TYPE
    case 2: return ns.ENGINE
    case 3: return ns.TABLE_COMMENT
    }
    
    return ""
}
// получение таблиц
func GetTablesProp(bd_name string)  string {
    
    rows := doQuery("SELECT TABLE_NAME, TABLE_TYPE, ENGINE, IFNULL(TABLE_COMMENT, '') FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=?", bd_name)
	
	if(rows == nil) {
    	return ""
	}	
	
    var result bytes.Buffer
    w := io.Writer(&result)
    Encode := json.NewEncoder(w)

    for rows.Next() {
        
        var row RecordTables
//         var i interface {}
        
//         i = &row
        rows.Scan( &row.TABLE_NAME, &row.TABLE_TYPE, &row.ENGINE, &row.TABLE_COMMENT)
        
        err := Encode.Encode(row)
    	if err != nil {
    		fmt.Println("error:", err)
    	}
        
    }
    
    return result.String()
    
}
    
func main () {
        
    doConnect()
    
    switch ( len( os.Args ) ) {
        case 1:
         fmt.Println( GetTablesProp("allservi") )
        case 2: 
         fmt.Println( GetColumnsProp( os.Args[1] ) )
        case 3: 
         fmt.Println( GetColumnsProp( os.Args[1] ) )
        
    }

}