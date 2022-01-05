<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package OceanWP WordPress theme
 */

get_header(); ?>


<section id="primary">


<img class="hero_overskrift" src="<?php echo get_stylesheet_directory_uri()?>/images/hero_produkter.png" alt="Overskrift til produktside">

    <div id="main" class="site-main">

    <div class="tekst_boks_produkter">
        <h1>Lamper</h1>
        <p>Made By Nicholas laver unikke håndlavede lamper, som fanger ens opmærksom med dets smukke farver og former. Vores mission er at lave smukke, unikke, funktionelle og kvalitetslamper som passer ind i enhver indretning. Nu skal du ikke længere tage valget mellem vintage og det elegante look, for her får du hele pakken.</p>
    </div>

    <div class="dropdown-menu">

    <div class="dropdown">
  	<button class="dropbtn-categori">FARVE ↓</button>
    <nav class="dropdown-content-first dropdown-content" id="categori-filtrering"><div class="filter valgt" data-cat="alle">Alle</div></nav>
    </div>

	<div class="dropdown">
  	<button class="dropbtn-kategori">HØJDE ↓</button>
    <nav class="dropdown-content" id="kategori-filtrering"><div class="filter valgt" data-kat="alle">Alle</div></nav>
    </div>

    <div class="dropdown">
  	<button class="dropbtn-kategori2">PRIS ↓</button>
    <nav class="dropdown-content" id="kategori2-filtrering"><div class="filter valgt" data-kat2="alle">Alle</div></nav>
    </div>

    </div>

        <section id="produkter-oversigt"></section>
       
    </div>

    <template>
      <article id="artikel">
        <figure class="image_boks">
        <img class="image" src="" alt="" />
        <div class="image_overlay">
            <button class="produkt_tilføj">SE PRODUKT</button>
        </div>
        </figure>
        <div class="template-tekst">
        <h2 class="titel"></h2>
		<p class="pris"></p>
        </div>
        </article>
    </template>

	<script>

    console.log("page products");

	const siteUrl = "<?php echo esc_url( home_url( '/' ) ); ?>";
	let produkter = [];
	let categories = [];
	let kategorier = [];
    let kategorier2 = [];
	const container = document.querySelector("#produkter-oversigt");
	const temp = document.querySelector("template");
	let filterFarve = "alle";
	let filterHoejde = "alle";
    let filterPriser = "alle";

	document.addEventListener("DOMContentLoaded", start);

	function start() {
		console.log("id er", <?php echo get_the_ID() ?>);
		console.log(siteUrl);
		
		getJson();
	}


	async function getJson() {
            //hent alle custom posttypes produkter
            const url = siteUrl +"wp-json/wp/v2/produkt?per_page=100";
            //hent basis categories
            const catUrl = "https://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/wp-json/wp/v2/categories";
             //hent custom category: kategori
            const katUrl = "https://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/wp-json/wp/v2/kategori";
            //hent custom category: kategori2
            const kat2Url = "https://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/wp-json/wp/v2/pris";
            let response = await fetch(url);
            let catResponse = await fetch(catUrl);
            let katResponse = await fetch(katUrl);
            let kat2Response = await fetch(kat2Url);
            produkter = await response.json();
            categories = await catResponse.json();
            kategorier = await katResponse.json();
            kategorier2 = await kat2Response.json();

            visProdukter();
            opretKnapper();
        }

		function opretKnapper(){
            categories.forEach(cat=>{
               //console.log(cat.id);
                if(cat.name != "Uncategorized"){
                document.querySelector("#categori-filtrering").innerHTML += `<div class="filter" data-cat="${cat.id}">${cat.name}</div>`
                }
            })
			    kategorier.forEach(kat=>{
               //console.log(kat.id);
                document.querySelector("#kategori-filtrering").innerHTML += `<div class="filter" data-kat="${kat.id}">${kat.name}</div>`
            })
                kategorier2.forEach(kat2=>{
               //console.log(kat2.id);
                document.querySelector("#kategori2-filtrering").innerHTML += `<div class="filter" data-kat2="${kat2.id}">${kat2.name}</div>`
            })

            addEventListenersToButtons();
            }


        function visProdukter() {
            console.log(produkter);
            container.innerHTML = "";
            console.log({filterFarve});
            console.log({filterHoejde});
            console.log({filterPriser});
            produkter.forEach(produkt => {
                //tjek filterFarve, filterHoejde og filterPriser til filtrering
                if ((filterFarve == "alle"  || produkt.categories.includes(parseInt(filterFarve))) 
                && (filterHoejde == "alle"  || produkt.kategori.includes(parseInt(filterHoejde))) 
                && (filterPriser == "alle"  || produkt.pris.includes(parseInt(filterPriser)))) {
                    const klon = temp.cloneNode(true).content;
                    console.log(klon);
                    klon.querySelector(".titel").textContent = produkt.title.rendered;
                    klon.querySelector(".image").src = produkt.billede.guid;
					klon.querySelector(".pris").textContent = produkt.salgspris + " " + "kr.";
                    klon.querySelector("article").addEventListener("click", () => {
                        location.href = produkt.link;
                    })
                    container.appendChild(klon);
                } else{
                    console.log("der er ingen produkter");
                }
            })
        }

		function addEventListenersToButtons() {
			document.querySelectorAll("#categori-filtrering div").forEach(elm => {
                elm.addEventListener("click", filtreringCategori);
            })

            document.querySelectorAll("#kategori-filtrering div").forEach(elm => {
                elm.addEventListener("click", filtreringKategori);
            })     

             document.querySelectorAll("#kategori2-filtrering div").forEach(elm => {
                elm.addEventListener("click", filtreringKategori2);
            })      
        }



        function filtreringCategori() {
            filterFarve = this.dataset.cat;
             //fjern .valgt fra alle
            document.querySelectorAll("#categori-filtrering .filter").forEach(elm => {
                elm.classList.remove("valgt");
            });
            //tilføj .valgt til den valgte
            this.classList.add("valgt");
            visProdukter();
        }

		function filtreringKategori() {
            filterHoejde = this.dataset.kat;
            //fjern .valgt fra alle
            document.querySelectorAll("#kategori-filtrering .filter").forEach(elm => {
                elm.classList.remove("valgt");
            });
            //tilføj .valgt til den valgte
            this.classList.add("valgt");
            visProdukter();
        }

        function filtreringKategori2() {
            filterPriser = this.dataset.kat2;
            //fjern .valgt fra alle
            document.querySelectorAll("#kategori2-filtrering .filter").forEach(elm => {
                elm.classList.remove("valgt");
            });
            //tilføj .valgt til den valgte
            this.classList.add("valgt");
            visProdukter();
        }

        </script>

		
	</section><!-- #primary -->

<?php get_footer(); ?>
