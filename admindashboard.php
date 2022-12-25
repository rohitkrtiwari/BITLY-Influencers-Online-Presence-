<?php
// echo "welcome php admindashboard ";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body class="deep-purple">
<div class="row">
<div class="col 16 offset-13" style="margin-top:10%">
<nav class="white">
  <div class="nav-wrapper">
    <form  id="search_post_form">
      <div class="input-field">
        <input id="search_post_input" type="search" required>
        <label class="label-icon" for="search"><i class="material-icons black-text">search</i></label>
        <i class="material-icons black-text"  id ="search_field_close_btn">close</i>
      </div>
    </form>
  </div>
</nav>
</div>
</div>
<div id="search_output" style="display:none"></div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
  close_btn=document.getElementById("search_field_close_btn")
  form=document.getElementById("search_post_form")
  search_input=document.getElementById("search_post_input")
  output=document.getElementById("search_output")
  form.addEventListener("submit",submitnot)
  function submitnot(e)
  {
    e.preventDefault();
  }
  close_btn.onclick=function()
   {
    search_input.value=''
    output.innerHTML=""
    output.style.display="none"
   }
  {
   search_input.addEventListener("keydown",(e)=>{
   output.style.display="block"
   output.innerHTML=`<div class="progress">
      <div class="indeterminate"></div>
  </div>`
   var q=e.target.value
   const xhr=new XMLHttpRequest();
   xhr.open("GET","fetch.php?q="+q,true)
   xhr.onload=function()
   {
    console.log(q)
   if(xhr.status>=1 )
    {
     output.innerHTML=''
     output.innerHTML=xhr.responseText
    }
   }
   if(q.length>=2)
   xhr.send();
   if(q.length==0)
   {
    output.innerHTML=''
    output.style.display="none"
   }
  })
  }
</script>
</body>
</html>

