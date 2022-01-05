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
get_header();
?>

<head>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()?>/custom.css">
</head>

	    <section id="primary">
		<div id="main-single" class="site-main">

        <!-- breadcrums på singleview -->
        <ul class="breadcrumb">
        <li><a href="http://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/">Hjem</a></li>
	    <li><a href="http://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/produkter/">Produktoversigt</a></li>
        <li>Produktdetaljer</li>
        </ul>

        <!-- indholdet article tag er html skabelon for det enkelte ur -->
        <article id="artikel_single">

        <img class="pic_single" src="" alt="" />
        <div class="div_single">
        <h1 class="titel_single"></h1>
        <p class="beskrivelse_single"></p>
        <p class="pris_single"></p>

        <button class="tilføj_single">TILFØJ TIL KURV</button>

        <div id="oplysninger_section">
        <p>Specifikationer</p>
        <div class="produkt_detaljer">
        <div class="line1"></div>
        <div class="line2"></div>
        </div>
        </div>

        <p class="specifikationer_dropdown"></p>

        <div id="levering_section">
        <p>Levering</p>
        <div class="produkt_detaljer_levering">
        <div class="line1"></div>
        <div class="line2_levering"></div>
        </div>
        </div>

        <p class="levering_dropdown"></p>

        </article>

        </div> <!-- #main-single -->

        <div id="popup_produkter" class="hidden">
        <button id="luk_produkter">X</button>
        <h3>
            Produktet er tilføjet <br />
            til kurven
        </h3>
        </div>


        <h2 id="h2_lignende">Se lignende produkter</h2>

        <section id="andre_produkter"></section>

        <template>
        <article class="lignende_produkter">
        <figure class="image_boks">
        <img class="image" src="" alt="" />
        <div class="image_overlay">
            <button class="produkt_tilføj">LÆS MERE</button>
        </div>
        </figure>
        <div class="template-tekst">
        <h2 class="titel"></h2>
		<p class="pris"></p>
        </div>
        </article>
        </template>

        </div><!-- #main-single -->

        <script>

        let produkt;
        let produkter;
		const url = "https://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/wp-json/wp/v2/produkt/"+<?php echo get_the_ID() ?>;
        const produkterUrl = "https://katjalevring.dk/kea/10_eksamensprojekt/made_by_nicholas/wp-json/wp/v2/produkt?per_page=3";
        const produktTemplate = document.querySelector("template");
	    const ekstra_info = document.querySelector("#oplysninger_section");
        const ekstra_info_levering = document.querySelector("#levering_section");
        const specifikationer_beskrivelse = document.querySelector(".specifikationer_dropdown");
        const levering_beskrivelse = document.querySelector(".levering_dropdown");
        const linje = document.querySelector(".line2");
        const linje2 = document.querySelector(".line2_levering");
        const tilføj_knap = document.querySelector(".tilføj_single");
        const popup_produkter = document.querySelector("#popup_produkter");
        const luk_popUpProdukter = document.querySelector("#luk_produkter");

        // PopUp single view

        document.addEventListener("DOMContentLoaded", start);

        function start() {
        tilføj_knap.addEventListener("click", popUpProdukter);
        luk_popUpProdukter.addEventListener("click", lukPopUp);
        }

        function lukPopUp() {
            popup_produkter.classList.add("hidden");
        }

        function popUpProdukter() {
            popup_produkter.classList.remove("hidden");
        }


        // PopUp single view slut

        specifikationer_beskrivelse.style.display = "none";
        ekstra_info.addEventListener("click", foldOut);

        levering_beskrivelse.style.display = "none";
        ekstra_info_levering.addEventListener("click", foldOutLevering);
        
		async function getJson() {
  		const response = await fetch(url);
  		produkt = await response.json();
        console.log(produkt);
  		visProdukt();
		}

        async function Json() {
  		const result = await fetch(produkterUrl);
  		produkter = await result.json();
        console.log(produkter);
  		visAndreProdukter();
		}

        function foldOut() {
        if (specifikationer_beskrivelse.style.display == "none") {
            specifikationer_beskrivelse.style.display = "block";
        linje.style.display = "none";
        } else {
            specifikationer_beskrivelse.style.display = "none";
        linje.style.display = "block";
        }
    }


    function foldOutLevering() {
        if (levering_beskrivelse.style.display == "none") {
            levering_beskrivelse.style.display = "block";
            linje2.style.display = "none";
        } else {
            levering_beskrivelse.style.display = "none";
            linje2.style.display = "block";
        }
    }

        function visProdukt() {
            document.querySelector(".titel_single").textContent = produkt.title.rendered;
            document.querySelector(".pic_single").src = produkt.billede.guid;
            document.querySelector(".beskrivelse_single").textContent = produkt.beskrivelse;
            document.querySelector(".pris_single").textContent = produkt.salgspris + " " + "kr.";
            document.querySelector(".specifikationer_dropdown").innerHTML = produkt.beskrivelse_dropdown;
            document.querySelector(".levering_dropdown").innerHTML = produkt.shipping_dropdown;
        }

            
        function visAndreProdukter() {
        const liste = document.querySelector("#andre_produkter");
        liste.textContent = "";
        produkter.forEach((produkt) => {
        let klon = produktTemplate.cloneNode(true).content;
        klon.querySelector(".titel").textContent = produkt.title.rendered;
        klon.querySelector(".image").src = produkt.billede.guid;
		klon.querySelector(".pris").textContent = produkt.salgspris + " " + "kr";
	    klon.querySelector(".lignende_produkter").addEventListener("click", () => {
        location.href = produkt.link;

        });

        liste.appendChild(klon); 
      
        });
    }

        getJson();

        Json();

    </script>

		
	</section><!-- #primary -->

<?php
get_footer();
