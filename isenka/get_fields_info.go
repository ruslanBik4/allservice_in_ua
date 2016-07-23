package main
import (
    "fmt"
    "database/sql"
    "os"
    "io"
    "encoding/json"
    "bytes"
    )
// получение значений полей для форматирования данных
func GetFieldProp(field_name string)  *sql.Rows {

	rows := doQuery("select * from field_names where field_name = ?", field_name);
	
    	 return rows;
}

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
            IS_VIEW int
    		
        }
        var row RecordCategory
    var result bytes.Buffer
//     []byte(` [{
//         "Поля таблицы " : table_name }}]`)
        
	rows := doQuery("select title, type_input, is_view, COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT, IS_NULLABLE, CHARACTER_SET_NAME, concat( '<', C.COLUMN_COMMENT, '>') from INFORMATION_SCHEMA.COLUMNS C join allservi.field_names on (field_name = COLUMN_NAME) where TABLE_NAME = ?", table_name);
	
	if(rows == nil) {
    	return ""
	}	
	
    w := io.Writer(&result)
    Encode := json.NewEncoder(w)

    for rows.Next() {
        
        rows.Scan( &row.TITLE, &row.TYPE_INPUT, &row.IS_VIEW, &row.COLUMN_NAME, &row.DATA_TYPE, &row.COLUMN_DEFAULT, &row.IS_NULLABLE, &row.CHARACTER_SET_NAME, &row.COLUMN_COMMENT )
        
        err := Encode.Encode(row)
    	if err != nil {
    		fmt.Println("error:", err)
    	}

    }
    
    	 return result.String();
}

// получение таблиц
func GetTablesProp(bd_name string)  string {
    
    rows := doQuery("SELECT TABLE_NAME, TABLE_TYPE, ENGINE, TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=?", bd_name)
	
	if(rows == nil) {
    	return ""
	}	
	
// 	sFormat := "%-25s %-16s %-6s %12s"
	result := "Таблицы БД allservi"
// 	fmt.Println( fmt.Sprintf(sFormat, "Имя", "Тип", "Engine", "Комментарий") )
    for rows.Next() {
        
        type RecordCategory struct {
    		TABLE_NAME   string
    		TABLE_TYPE string
     		ENGINE string
            TABLE_COMMENT string    		
        }
        var row RecordCategory
        rows.Scan( /*&row.ID,*/ &row.TABLE_NAME, &row.TABLE_TYPE, &row.ENGINE, &row.TABLE_COMMENT/*, &row.Reg_expr, &row.Video */)
        
        b, err := json.Marshal(row)
    	if err != nil {
    		fmt.Println("error:", err)
    	}
        result += string(b)
//         fmt.Printf(sFormat, row.TABLE_NAME, row.TABLE_TYPE, row.ENGINE, row.TABLE_COMMENT)
        
    }
    
    return result
    
}
    
func main () {
        
    doConnect()
    
    switch ( len( os.Args ) ) {
        case 1:
         fmt.Println( GetTablesProp("allservi") )
        case 2: 
         fmt.Print( GetColumnsProp( os.Args[1] ) )
        case 3: 
         fmt.Println( GetColumnsProp( os.Args[1] ) )
        
    }

}ect()
    
    switch ( len( os.Args ) ) {
        case 1:
         fmt.Println( GetTablesProp("allservi") )
        case 2: 
         fmt.Println( GetColumnsProp( os.Args[1] ) )
        case 3: 
         fmt.Println( GetColumnsProp( os.Args[1] ) )
        
    }

}Prop( os.Args[1] ) )
        
    }

}