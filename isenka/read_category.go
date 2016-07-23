package main
import (
    "fmt"
    "os"
    "strconv"
    "encoding/json"
    )

func GetCategoryByParent (key_parent int ) string {
 
   result := ""
   
   rows := doQuery("select key_category, name, text_task from category where key_parent  = ? order by name", key_parent)
    
    for rows.Next() {
        var name, task, subMenu string 
        var key_category int
        rows.Scan(&key_category, &name, &task)
        subMenu = GetCategoryByParent (key_category)
        
        if ( subMenu > "") {
            subMenu = " > \n " + subMenu + "<<"
            
        }
            
        result +=  fmt.Sprintf( "%s;%s;%s;%s \n", key_category, name, GetTaskFromCursWork(key_category, task), subMenu )
    }
   
   return result
}

func GetTaskFromCursWork(key_category int, text string) string {
     rows := doQuery("select w.name, t.text from course_work w join course_task t using(key_course_work) where t.key_category  = ?", key_category)
    if rows == nil {
        return fmt.Sprintf("During execute query error = %q") 
   }
    
    if (text > ""){
     text = text + ". "
     }

    for rows.Next() {
        var name, task string
        rows.Scan(&name, &task)
        text += fmt.Sprintf( "Курсовая <b>%s</b> Сделать: %s", name, task )
    }
    
       
    return text
}

func GetCategoryById (id int ) string  {
    
   result := ""
     rows := doQuery("select key_category, name, short_text, long_text, text_task, reg_expr, video from category where key_category = ?", id)

    if rows == nil {
        return fmt.Sprintf(" I take error = %q")
    }
    
    for rows.Next() {
        
        type RecordCategory struct {
    		ID     int
    		Name   string
    		Short_text string
    		Long_text string
     		Text_task string
            Reg_expr string
            Video string
    		
        }
        var row RecordCategory
        
        rows.Scan(&row.ID, &row.Name, &row.Short_text, &row.Long_text, &row.Text_task, &row.Reg_expr, &row.Video )
        
        b, err := json.Marshal(row)
    	if err != nil {
    		fmt.Println("error:", err)
    	}
        result += string(b)
    }
   return result
}

func main () {
        
    doConnect()
    
    if (len( os.Args )  == 1) {
        
        fmt.Println(  GetCategoryByParent( 0 ) )
        return
        
    }

    id, err := strconv.Atoi( os.Args[1] )
    
     if (err != nil) {
         panic("Not valid")
     }

    switch ( len( os.Args ) ) {
//         case 1: 
        case 2 : 
        fmt.Println(  GetCategoryById( id ) );
        case 3: 
        fmt.Println(  GetCategoryByParent( id ) );
        
    }
}