<?php

/**
 * Add Required links and header
 * @package AgriFlex3
 * @since 1.0
 */
class AgriFlex_RequiredDOM
{

    public function __construct()
    {

        // Add bar of AgriLife agencies
        $this->add_agency_bar();

        // Remove default Genesis footer
        $this->remove_genesis_footer();

        // Add required links to footer
        //$this->build_footer_content();

        add_action('genesis_before', array($this, 'build_footer_content'));

        // Alter header title
        //$this->alter_header_title();

        //$this->add_unit_title();

        // Render the social icons in header
        //$this->render_header_social();

        //* Reposition the breadcrumbs
        $this->move_genesis_breadcrumbs();

        // Alter header tags for SEO
        add_filter( 'genesis_seo_title', array($this, 'alter_title_tag'), 10, 3 );
        add_filter( 'genesis_seo_description', array($this, 'alter_description_tag'), 10, 5 );

    }

    /**
     * Add agency bar to top of page
     * @since 1.0
     * @return void
     */
    private function add_agency_bar()
    {

        add_action('genesis_before', array($this, 'render_agency_bar'));

    }

    /**
     * Replace heading tag with div
     *
     * @param $title The title text
     * @param $inside
     * @param $wrap
     *
     * @return string
     */

    public static function alter_title_tag( $title, $inside, $wrap ) {

        return preg_replace( '/\b'.$wrap.'\b/', 'div', $title );

    }

    /**
     * Replace description tag with div
     *
     * @param $description The description text
     * @param $inside
     * @param $wrap
     *
     * @return string
     */
    public function alter_description_tag($description, $inside, $wrap)
    {
        // $wrap may be empty for some reason
        if (empty($wrap)) {
            preg_match('/\w+/', $description, $results);
            $wrap = $results ? $results[0] : 'h2';
        }

        // $inside may be empty for some reason
        if (empty($inside)) {
            $results = preg_split('/<\/?' . $wrap . '[^>]*>/', $description);
            $inside = sizeof($results) > 1 ? $results[1] : esc_attr(get_bloginfo('description'));
        }

        // Place wildcards where needed
        $description = preg_replace('/\b' . $wrap . '\b/', '%1$s', $description);
        if (!empty($inside)) {
            $description = str_replace($inside, '%2$s', $description);
        }

        // Add the site title before the description
        $wrap = 'div';
        $description = sprintf($description, $wrap, $inside);

        return $description;
    }

    /**
     * Modify the header title
     * @since 1.0
     * @return string
     */
    private function alter_header_title()
    {
        add_filter('genesis_seo_title', 'sp_seo_title', 10, 3);
        function sp_seo_title()
        {

            $title = '<h4 class="site-title" itemprop="agency"><a href="https://agrilife.tamu.edu" title="Texas A&amp;M AgriLife"><span>Texas A&amp;M AgriLife</span></a></h4>';
            return $title;
        }

    }


    /**
     * Add required links to bottom of page
     * @since 1.0
     * @return void
     */
    private function add_unit_title()
    {

        add_action('genesis_site_description', array($this, 'unit_title'), 1);

    }
    public function unit_title()
    {
        $wrap = 'div';
        $inside = sprintf('<a href="%s" title="%s"><span>%s</span></a>', esc_attr(get_bloginfo('url')), esc_attr(get_bloginfo('name')), get_bloginfo('name'));
        $title = sprintf('<%s class="site-unit-title" itemprop="headline">%s</%s>', $wrap, $inside, $wrap);
        echo $title;
    }

    /**
     * Add required links to bottom of page
     * @since 1.0
     * @return void
     */
    public function build_footer_content()
    {

        add_action('genesis_footer', array($this, 'render_required_links'));
        add_action('genesis_footer', array($this, 'render_tamus_logo'));

    }

    /**
     * Remove default Genesis footer
     * @since 1.0
     * @return void
     */
    private function remove_genesis_footer()
    {

        remove_action('genesis_footer', 'genesis_do_footer');

    }

    /**
     * Move Genesis Breadcrumbs
     * @since 1.0
     * @return void
     */
    private function move_genesis_breadcrumbs()
    {

        if(!defined('AG_EXT_DIRNAME') && !defined('AG_EXTUNIT_DIRNAME') && !defined('AG_EXTRES_DIRNAME') ) {
            remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
            add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );
        }

        add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
        function sp_breadcrumb_args( $args ) {
            //$args['home'] = 'Home';
            $args['sep'] = ' > ';
            //$args['list_sep'] = ', '; // Genesis 1.5 and later
            //$args['prefix'] = '<div class="breadcrumb">';
            //$args['suffix'] = '</div>';
            //$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
            //$args['heirarchial_categories'] = true; // Genesis 1.5 and later
            //$args['display'] = true;
            $args['labels']['prefix'] = '';
            //$args['labels']['author'] = 'Archives for ';
            //$args['labels']['category'] = 'Archives for '; // Genesis 1.6 and later
            //$args['labels']['tag'] = 'Archives for ';
            //$args['labels']['date'] = 'Archives for ';
            //$args['labels']['search'] = 'Search for ';
            //$args['labels']['tax'] = 'Archives for ';
            //$args['labels']['post_type'] = 'Archives for ';
            //$args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
            return $args;
        }

    }

    /**
     * Render the agency header bar
     * @since 1.0
     * @return string
     */
    public function render_agency_bar()
    {

        $output = '
          <div class="agency-bar">
            <div class="agency-wrap">
                <ul>
		            <li class="tfs-item"><a href="https://tfsweb.tamu.edu/"><span>Texas A&amp;M Forest Service</span></a></li><li class="tvmdl-item"><a href="http://tvmdl.tamu.edu/"><span>Texas A&amp;M Veterinary Medical Diagnostics Laboratory</span></a></li><li class="ext-item"><a href="https://agrilifeextension.tamu.edu/"><span>Texas A&amp;M AgriLife Extension Service</span></a></li><li class="res-item"><a href="https://agriliferesearch.tamu.edu/"><span>Texas A&amp;M AgriLife Research</span></a></li><li class="college-item"><a href="https://aglifesciences.tamu.edu/"><span>Texas A&amp;M College of Agrculture and Life Sciences</span></a></li>
		        </ul>
		    </div>
		  </div>
		  ';

        echo $output;

    }

    /**
     * Render required links
     * @since 1.0
     * @return string
     */
    public static function render_required_links()
    {

        $output = '
            <div class="footer-container">
                <ul class="req-links">
			        <li><a href="https://agrilife.tamu.edu/required-links/compact/">Compact with Texans</a></li>
			        <li><a href="https://agrilife.tamu.edu/required-links/privacy/">Privacy and Security</a></li>
			        <li><a href="https://itaccessibility.tamu.edu/" target="_blank">Accessibility Policy</a></li>
			        <li><a href="https://dir.texas.gov/resource-library-item/state-website-linking-privacy-policy" target="_blank">State Link Policy</a></li>
			        <li><a href="https://www.tsl.texas.gov/trail/index.html" target="_blank">Statewide Search</a></li>
			        <li><a href="https://aggie.tamu.edu/financial-aid/veterans" target="_blank">Veterans Benefits</a></li>
			        <li><a href="https://fch.tamu.edu/programs/military-programs/" target="_blank">Military Families</a></li>
			        <li><a href="https://secure.ethicspoint.com/domain/en/report_custom.asp?clientid=19681" target="_blank">Risk, Fraud &amp; Misconduct Hotline</a></li>
			        <li><a href="https://gov.texas.gov/organization/hsgd" target="_blank">Texas Homeland Security</a></li>
			        <li><a href="https://veterans.portal.texas.gov/">Texas Veterans Portal</a></li>
			        <li><a href="https://agrilifeas.tamu.edu/hr/diversity/equal-opportunity-educational-programs/" target="_blank">Equal Opportunity</a></li>
			        <li class="last"><a href="https://agrilife.tamu.edu/required-links/orpi/">Open Records/Public Information</a></li>
		        </ul>
            </div>';

        echo $output;

    }

    /**
     * Render TAMUS logo
     * @since 1.0
     * @return string
     */
    public static function render_tamus_logo()
    {

        $output = '
            <div class="footer-container-tamus">
                <a href="http://tamus.edu/" title="Texas A&amp;M University System"><img class="footer-tamus" src="'.AF_THEME_DIRURL.'/img/footer-tamus-maroon.png" alt="Texas A&amp;M University System Member" /></a>
            </div>';

        echo $output;

    }

    /**
     * Add the correct Typekit
     * @since 1.0
     * @todo Replace with async js and deal with FOUC
     * @return string
     */
    public static function add_typekit($key) {

        if( !is_admin() ) :
            ?>
            <script type="text/javascript" src="//use.typekit.net/<?php echo $key; ?>.js"></script>
            <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        <?php
        endif;

    }


}
