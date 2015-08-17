$(document).ready(function(){
   var quickForm=$('#quickForm');
   var contactForm=$('#contactForm');
   var btnQuickSubmit=$('#btnQuickSubmit');
   var btnContactUsSubmit=$('#btnContactUsSubmit');
   var errors=[];
   if(quickForm!=null && quickForm.length>0){
       $(btnQuickSubmit).click(function(e){
		 errors=[];
		  
		  var tbEmail=$('#tbEmail');
		  
		  
		  var re_name=/^([a-zA-Z]{2,}\s*)+$/;
          var re_email=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
         var re_phone=/^[\d\s\/-]+$/;


		 
		 if(!tbEmail.val().match(re_email)){
		    tbEmail.css({'border-color':'#FF0000'});
			errors.push('Field email is not valid !!!!');
		 }
		 

		 

		 var message="";
		 if(errors.length>0){
		    for(var i=0; i<errors.length; i++){
			   message+="  "+errors[i]+"\n";
			}
			alert(message);
			
		 }else{
			$.ajax({
                type: "POST",
                async: false,
                url: $('#quickForm').attr('action'),
                data: $('#quickForm').serialize(),
                dataType: "json",
                success: function(resp) {
				    var text="";
                    $.each(resp,function(index,item){
					    text+=" "+item;
					});
				
					if(text==""){
					   $.each(resp,function(index,item){
					    text+=" "+item;
					    });
						tbName.val('');
		                tbEmail.val('');
		                tbPhone.val('');
		                taMessage.val('');
					}
                    alert(text);
                },
                error: function(jqxhr, txt, err) {
                    alert('Server Error !!!');
                }
            });
		 }
          return false;
	   });
   }
   
   if(contactForm!=null && contactForm.length>0){
       $(btnContactUsSubmit).click(function(e){
		 errors=[];
		  var tbFirstName=$('#tbFirstName');
		  var tbLastName=$('#tbLastName');
		  var tbEmail=$('#tbContactEmail');
		  var tbPhone=$('#tbContactPhone');
		  var tbSubject=$('#tbSubject');
		  var taMessage=$('#taContactMessage');
		  var re_name=/^([a-zA-Z]{2,}\s*)+$/;
          var re_email=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
         var re_phone=/^[\d\s\/-]+$/;
		 var re_subject=/^([a-zA-Z]{1,}\s*\d*)+$/;

         if(!tbFirstName.val().match(re_name)){
		    tbFirstName.css({'border-color':'#FF0000'});
			errors.push('Field first name is not valid !!!');
		 }
		 
		 if(!tbLastName.val().match(re_name)){
		    tbLastName.css({'border-color':'#FF0000'});
			errors.push('Field last name is not valid !!!');
		 }
		 
		 if(!tbEmail.val().match(re_email)){
		    tbEmail.css({'border-color':'#FF0000'});
			errors.push('Field email is not valid !!!');
		 }
		 
		 if(!tbPhone.val().match(re_phone)){
		    tbPhone.css({'border-color':'#FF0000'});
			errors.push('Field phone is not valid!!!');
		 }
		 
		 if(!tbSubject.val().match(re_subject)){
		    tbSubject.css({'border-color':'#FF0000'});
			errors.push('Field subject is not valid');
		 }
		 
		 if(taMessage.val().length>250){
		    taMessage.css({'border-color':'#FF0000'});
			errors.push('Content of field message have more than 250 charachters');
		 }
		 
		 var message="";
		 if(errors.length>0){
		    for(var i=0; i<errors.length; i++){
			   message+="  "+errors[i]+"\n";
			}
			alert(message);
			return false;
		 }else{
			return true;
		 }
          
	   });
   }
});
if(!Object.keys){
    Object.keys=function(obj){
        var keys=[],k;
        for(k in obj)
            if(Object.prototype.hasOwnProperty.call(obj,k))
                keys.push(k);
        return keys;
    };
}
