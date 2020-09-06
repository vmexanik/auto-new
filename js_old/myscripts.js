$(document).ready(function(){

    $("#loginform").validate({
        
       rules:{ 
        
            email:{
                required: true,
                email: true,
               // remote: ,
            },
            name:{
            	required: true,
                minlength: 3,
            },
            city:{
            	required: true,
            },
            address:{
            	required: true,
            },
            phone:{
            	required: true,
            },
       },
       
       messages:{
        
            email:{
                required: "Это поле обязательно для заполнения",
                email: "Адрес email должен быть правильным",
            },
            name:{
                required: "Это поле обязательно для заполнения",
            },
            city:{
                required: "Это поле обязательно для заполнения",
            },
            address:{
                required: "Это поле обязательно для заполнения",
            },
            phone:{
                required: "Это поле обязательно для заполнения",
            },
       }
        
    });


}); //end of ready