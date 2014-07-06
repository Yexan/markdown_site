<?php

  // Éléments à ignorer
  $ignoreList = array ('.','..','.git');

  // Liste des Markdowns
  $dir_md=opendir("./md/");
  $markdowns = '[';
  while ($file = readdir($dir_md)) {
    if (!in_array($file,$ignoreList)) {

      $markdowns .= '{"titre": "'.substr($file, 0, -3).'", "contenu": "'.preg_replace('/^\s+|\n|\r|\s+$/m', '\n', (htmlspecialchars(file_get_contents("./md/".$file)))).'"},';
    }
  }
  closedir($dir_md);

  // On enlève la dernière virgule
  $markdowns =substr($markdowns, 0, -1);

  // On ferme le tableau
  $markdowns .=']';


  // Variables de l'include head.php
  $title        = "Blog";
  $description  = "Ceci est un blog de test réalisé à partir de fichiers Markdown";
  $keywords     = "";
  $copyright    = "Adrien Martinet 2014";
  $author       = "Adrien Martinet";

?>
<html lang="fr">
<head>
  <?php include('includes/head.php'); ?>
</head>
<body>

  <div class="container">

    <section class="five columns">
        <ul>
            <li dataContent="sommaire">Sommaire</li>
        </ul>
    </section>

    <article class="eleven columns">

    </article>

</div>

<script src="js/showdown.js"></script>
<script type="text/javascript">

  var markdowns = <?= $markdowns ?> ;

  var converter   = new Showdown.converter(),
  menu        = document.querySelector('section ul'),
  article     = document.querySelector('article');


  for (var i = 0; i < markdowns.length; i++) {
      if(markdowns[i]['titre'] != 'sommaire')
          menu.innerHTML = menu.innerHTML + '<li dataContent="'+markdowns[i]['titre']+'">' + markdowns[i]['titre'] + '</li>';
  };

  var menuLis = document.querySelectorAll('section li');

  for (var i=0; i<menuLis.length; i++){
      menuLis[i].addEventListener("click", changeContent, false);
  }

  function findIndexByKeyValue(arraytosearch, key, valuetosearch) {
      for (var i = 0; i < arraytosearch.length; i++) {
          if (arraytosearch[i][key] == valuetosearch) {
              return i;
          }
      }
      return null;
  }

  function changeContent(e) {
    if(!e) { e = window.event; }

    var pos = findIndexByKeyValue(markdowns, "titre", e.target.attributes.dataContent.value);
    
    article.innerHTML = converter.makeHtml(markdowns[pos]['contenu']);

  }

  //On initialise l'affichage du sommaire au chargement de la page
  var pos = findIndexByKeyValue(markdowns, "titre", "sommaire");
  article.innerHTML = converter.makeHtml(markdowns[pos]['contenu']);

</script>
</body>
</html>


