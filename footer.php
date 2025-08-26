 <footer>
     <div class="footer-part1">

         <figure class="logo-container-no-background">
             <a href="/" id="footer-logo-link">
                 <img class="logo" src="<?php echo get_template_directory_uri(); ?>/icons/terra coffee logo2 1.png"
                     alt="Coffee Company Logo" width="80" height="80" />
                 <div>
                     <figcaption>Terra Coffee</figcaption>
                     <figcaption class="footer-figcaption">ΚΑΦΕΣ ΜΕ ΡΙΖΕΣ ΣΤΗ ΓΗ</figcaption>
                 </div>
             </a>
         </figure>

         <nav class="footer-nav">
             <ul class="footer-links">
                 <li><a href="<?php echo get_permalink( get_page_by_path( 'products' ) ); ?>">Κατάλογος Προϊόντων</a>
                 </li>
                 <li><a href="<?php echo get_permalink( get_page_by_path( 'history' ) ); ?>">Η Ιστορία μας</a></li>
                 <li><a href="<?php echo get_permalink( get_page_by_path( 'news' ) ); ?>">Τα Νέα μας</a></li>
                 <li><a href="<?php echo get_permalink( get_page_by_path( 'contact' ) ); ?>">Επικοινωνία</a></li>
                 <li><a href="<?php echo get_permalink( get_page_by_path( 'login' ) ); ?>" class="login-link">Εγγραφή /
                         Είσοδος</a></li>
             </ul>
         </nav>
         <div class="socials">
             <a href="/"><img
                     src="<?php echo get_template_directory_uri(); ?>/icons/Facebook 1 Streamline Plump Solid - Free.svg"
                     alt="" /></a>
             <a href="/"><img
                     src="<?php echo get_template_directory_uri(); ?>/icons/Instagram Logo 2 Streamline Logos Block - Free.svg"
                     alt="" /></a>
             <a href="/"><img
                     src="<?php echo get_template_directory_uri(); ?>/icons/Pinterest Streamline Simple Icons.svg"
                     alt="" /></a>
             <a href="/"><img
                     src="<?php echo get_template_directory_uri(); ?>/icons/Tiktok Streamline Flex Solid - Free.svg"
                     alt="" /></a>
         </div>
     </div>
     <div class="footer-part2">
         <div class="footer-info">
             <span id="copyright">&copy; 2025 Terra Coffee. Με επιφύλαξη κάθε νόμιμου δικαιώματος.</span>
             <a href="/" id="privacy">Πολιτική Απορρήτου.</a>
             <a href="/" id="terms">Όροι και Προϋποθέσεις.</a>
         </div>
     </div>
 </footer>
 <?php wp_footer(); ?>
 </body>

 </html>