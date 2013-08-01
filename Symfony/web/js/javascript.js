function validateArticle()
	{
		var a=document.forms["article"]["title"].value;
		var b=document.forms["article"]["content"].value;
		
		if (a==null || a=="")
			{
			alert("Completati Titlul Articolului");
			return false;
			}
		if (b==null || b=="")
			{
			alert("Completati Continutul articolului!");
			return false;
			}
	}
	
function confirmDelete(id)
{
  var x = confirm("Sunteti sigur ca doriti stergerea articolului?");
  if (x)
      {
		window.location = '../admin/delete/'+id;
	  }
  else
    return false;
}