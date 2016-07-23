package main
import (
    "fmt"
    "database/sql"
    "os"
    )
        
func main () {
    
    doConnect()
    
    var rows *sql.Rows 
    
    switch ( len( os.Args ) ) {
        case 1:
         rows = doQuery("select * from category") 
        case 2: 
         rows = doQuery( os.Args[1] );
        case 3: 
         rows = doQuery( os.Args[1], os.Args[2:] );
        
    }

	if(rows == nil) {
    	return 
	}	
	
        columns, error := rows.Columns()
        if (error != nil) {
            return
        }
        
        for _, val := range columns {
            
         fmt.Printf( "%s, ", val  )
        } 
        type rowScan interface {
            sql.Scanner
//             row_lines [] string
        }
        
        var row [] interface {}
//         row := make([]string, len(columns))

    for rows.Next() {
        
        rows.Scan(&row)
        
        
        for key, val := range row {
            
//              rows.Scan(&row[key])
             
        fmt.Printf( "%+v, %s", val, key  )
           
        }
        
         fmt.Println()

    }
}