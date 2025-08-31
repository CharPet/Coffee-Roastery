<?php
/* Template Name: Products */
get_header();
?>

<main>
    <h2 class="products-title">Κατάλογος Προϊόντων</h2>
    <input type="text" id="product-search" placeholder="Αναζήτηση προϊόντων..." />

    <section class="products-grid">
        <div class="product-card" data-name="Espresso Crema" data-price="10.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 1" />
            <h3 class="product-name">Espresso Crema</h3>
            <p class="product-desc">Ο κλασικός, ιταλικός χαρακτήρας του καφέ: έντονο σώμα, γεμάτη γεύση και βελούδινη
                κρέμα που απογειώνει κάθε γουλιά.</p>
            <span class="product-price">€10.00</span>
            <br>
            <button class="buy-btn">Στο Καλάθι</button>
        </div>
        <div class="product-card" data-name="Single Origin – Αιθιοπία" data-price="12.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 2" />
            <h3 class="product-name">Single Origin – Αιθιοπία</h3>
            <p class="product-desc">Καφές από την καρδιά της Αφρικής, με εκλεπτυσμένα αρώματα γιασεμιού, νότες ζουμερών
                εσπεριδοειδών και μία κομψή, φρουτώδη οξύτητα που χαρίζει φρεσκάδα.</p>
            <span class="product-price">€12.00</span>
            <br>
            <button class="buy-btn">Στο Καλάθι</button>
        </div>
        <div class="product-card" data-name="Blend Terra – Υπογραφή Καβουρδίσματος" data-price="9.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 3" />
            <h3 class="product-name">Blend Terra – Υπογραφή Καβουρδίσματος</h3>
            <p class="product-desc">Η υπογραφή του Terra Coffee: επιλεγμένες ποικιλίες από Κεντρική και Νότια Αμερική,
                σε αρμονικό χαρμάνι με σοκολατένια επίγευση.</p>
            <span class="product-price">€9.00</span>
            <br>
            <button class="buy-btn">Στο Καλάθι</button>
        </div>
        <div class="product-card" data-name="Decaf Φυσικής Επεξεργασίας" data-price="10.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 4" />
            <h3 class="product-name">Decaf Φυσικής Επεξεργασίας</h3>
            <p class="product-desc">Απόλαυση χωρίς καφεΐνη, με φυσική επεξεργασία χωρίς χημικά. Ισορροπημένος καφές με
                απαλή γλυκύτητα και καθαρή επίγευση.</p>
            <span class="product-price">€10.00</span>
            <br>
            <button class="buy-btn">Στο Καλάθι</button>
        </div>
        <div class="product-card" data-name="Cold Brew Blend" data-price="11.00">
            <img src="<?php echo get_template_directory_uri(); ?>./images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 5" />
            <h3 class="product-name">Cold Brew Blend</h3>
            <p class="product-desc">Αργή εκχύλιση, δροσερό αποτέλεσμα. Φτιαγμένος για κρύα απόλαυση, με χαμηλή οξύτητα
                και νότες κακάο και ξηρών καρπών.</p>
            <span class="product-price">€11.00</span>
            <br>
            <button class="buy-btn">Στο Καλάθι</button>
        </div>
        <div class="product-card" data-name="Filter Roast – Γουατεμάλα" data-price="14.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 6" />
            <h3 class="product-name">Filter Roast – Γουατεμάλα</h3>
            <p class="product-desc">Ελαφρύ καβούρδισμα για φίλτρο, που αναδεικνύει φρουτώδεις νότες και ισορροπημένο
                χαρακτήρα. Ιδανικός για Chemex ή V60.</p>
            <span class="product-price">€14.00</span>
            <br>
            <button class="buy-btn">Στο Καλάθι</button>
        </div>
        <div class="product-card" data-name="Καφές Κατσαρόλας Παραδοσιακός" data-price="10.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 7" />
            <h3 class="product-name">Καφές Κατσαρόλας Παραδοσιακός</h3>
            <p class="product-desc">Ο καφές που μεγαλώσαμε μαζί του. Σιγοψημένος στην άμμο ή στο μπρίκι, με γεμάτο άρωμα
                και πλούσια παράδοση σε κάθε γουλιά.</p>
            <span class="product-price">€10.00</span>
            <br>
            <button class="buy-btn">Στο καλάθι</button>
        </div>
        <div class="product-card" data-name="Limited Harvest – Μικρές Παραγωγές" data-price="16.00">
            <img src="<?php echo get_template_directory_uri(); ?>/images/pzoswjy6kagrtcp0n4ri.webp" alt="Product 28" />
            <h3 class="product-name">Limited Harvest – Μικρές Παραγωγές</h3>
            <p class="product-desc">Σπάνιες παρτίδες από μικρούς καλλιεργητές με ιδιαίτερα προφίλ γεύσης. Αποκλειστικά
                για τους λάτρεις της εξερεύνησης.</p>
            <span class="product-price">€16.00</span>
            <br>
            <button class="buy-btn">Στο καλάθι</button>
        </div>

    </section>
    <div id="basket-modal" style="display:none;">
        <h3>Το Καλάθι σας</h3>
        <br><br>
        <ul id="basket-list"></ul>
        <div id="basket-total"></div>
        <button class="buy-btn" id="buy-btn-modal">Αγορά</button>
        <button id="close-basket">Κλείσιμο</button>
    </div>
</main>
<?php get_footer(); ?>