window.addEventListener( 'load', function()
{
  MenuPunktAktiv();
});

$('.hide').on('click',function(){
  $('nav ul').toggleClass('show');
});

function MenuPunktAktiv()
{
  var current = "" + window.location;
  var nav = document.getElementById( 'HovedMenu' );
  var anchor = nav.getElementsByTagName( 'a' );
  var li = nav.getElementsByTagName('li');


  if (current.search( ".php" ) == -1)
  {
    current = current;
    li = current;


  }

  for (var i = 0; i < anchor.length; i++)
  {
    if (current.toLowerCase() == anchor[i].href.toLowerCase())
    {
        li[i].className = "active";
        anchor[i].href = "#"

    }
  }
}