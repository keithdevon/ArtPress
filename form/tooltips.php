<?php
class ToolTips {
     private static $tooltips = array(
	    /* Config controls */
        "Save_Button"    => "Save this configuration",
        "Save_As_Button" => "Save a copy of this configuration under a new name",
        "Delete_Button"  => "Completely remove this configuration",
        "New_Button"     => "Create a new blank configuration",
        "Live_Button"    => "Make this configuration visible to the public",
     
        /* Top Level */
        "Global_Settings" => "Set the global colours, fonts and logo." 				  ,
        "Header_Tab"      => "Edit the header area, site title and site description." ,
        "Body_Tab"        => "Edit the main body of the site"                         ,
        "Menu_Tab"        => "Edit the main site navigation."                         ,
        "Sidebar_Tab"     => "Edit the site side bars."                               ,
        "Footer_Tab"      => "Edit the footer area."                                  ,
        "Gallery_Tab"     => "Edit the galleries page and site images."               ,
     
        /* Global Settings */
        "Global_Color"           => "Select a colour to be used throughout the site. Must be a hex value (e.g. #ff0000 for red).",
        "Global_Font_Size"       => "Select the base size in pixels. All other font sizes will be based on this and the modular scale.",
        "Global_Font_Size_Ratio" => "Select the modular scale that you wish to use. This will determine the available font sizes and line spacing.",
        "Global_Font_Family"     => "Select a font family (typeface) to be used throughout the site. You can select from a number of font stacks that produce the most consistent results across all systems.",
        "Logo_Image_Dropdown"    => "If you are have uploaded a logo image from ArtPress -> Images, you can use it by selecting it here.",

        /* Background */
        "Background_Image_Tab"           => "Background color and image settings",
        "Section_Background_Color"       => "Select the background color of the element from a list of Global Colors",
        "Background_Image_Toggle"        => "Select this option to use a background image that you have uploaded.",
        "Background_Image_Dropdown"      => "Select the image to use for the background.",
        "Background_Repeat"              => "Repeat the background image vertically (repeat-y), horizontally (repeat-x), both vertically and horizontally (repeat), or not at all (no-repeat).",
        "Background_Attachment"          => "Allow the background to scroll with the page (scroll) or have it fixed to the browser window (fixed).",
        "Background_Position"            => "Set the position of the background image in the horizontal and vertical axis. You can use pixels, ems or percentages (E.g. 10px, 1.2em or 25%).",
        "Background_Horizontal_Position" => "Set the horizontal position of the background image. You can use pixels, ems or percentages (E.g. 10px, 1.2em or 25%).",
        "Background_Vertical_Position"   => "Set the vertical position of the background image. You can use pixels, ems or percentages (E.g. 10px, 1.2em or 25%).",

        /* Typography */
        "Typography_Tab"		   => "Font and text settings",
        "Section_Foreground_Color" => "Select the text color of the element. These colors can be changed in the Palette tab.",
        "Section_Font"             => "Select the font family of the element. These font families can be changed in the Palette tab.",
        "Section_Font_Size"        => "Select the font size here. Font sizes are based on the base font size and the font size ratio. You can edit these in the Palette tab.",
        "Font_Style"               => "Select from Normal, Italic or Oblique.",
        "Font_Weight"              => "Sets the boldness of a font.",
        "Text_Align"               => "Align the text to the left, right, center or fully justified.",
        "Text_Decoration"          => "Apply underlines, strikethroughs, and more to the element. Note - underlines should normally be used only on links!",
        "Text_Transform"           => "Make your text all uppercase, lowercase or capitalized.",
        "Letter_Spacing"           => "Add or remove horizontal space from between the letters.",

        /* Header */
        "Header_Base"      => "Common settings for the whole header area. These can be overwritten by the following sections.",
        "Site_Title"       => "If you are not using a logo image, you can style the site title here.",
        "Site_Title_Hover" => "The mouse hover state of the 'Site Title'",
        "Site_Description" =>"The site description, or tagline, can be styled here. To change the text go to Settings in the left-hand admin menu.",

        /* Menu */
        "Menu_Base"         => "Common settings for the whole menu areas. These can be overwritten by the following sections.",
        "Link"              => "Style the menu links here.",
        "Link_Hover"        => "Style the mouse hover state of the menu links here.",
        "Sub_Menu"          => "If you are using sub-menus, you can style the drop-down list here.",
        "Sub_Menu_Hover"    =>  "Style the hover state of drop-down elements here.",
        "Current_Menu_Item" => "Style the menu link of the currently selected page. This is great for providing feedback on where the user is within the site.",

        /* Body */
        "Body_Base"        => "Common settings for the whole body area. These can be overwritten by the following sections.",
        "Page_Title"       => "The title of the current page can be styled here.",
        "Entry_Title"      => "The titles of blog posts, when listed, can be styled here.",
        "Widget_Title"     => "Titles of widgets within the body area can be styled here.",
        "H2"               => "Level 2 headings can be styled here.",
        "H3"               => "Level 3 headings can be styled here.",
        "H4"               => "Level 4 headings can be styled here.",
        "P"                => "Paragraph text can be styled here. This is the basic body text that will make up the content of most pages.",
        "UL"               => "Unordered lists are lists with bullet points.",
        "OL"               => "An ordered list is a numerically ordered list.",
        //"Link"             => "Style the links on your site here.",
        //"Link Hover"       => "Style the mouse hover state of your links here.",
        "Crumbs"           => "Breadcrumbs are a navigational helper and good for SEO. They are typically in the format YourSite / Parent Page / Current Page.",
        "Crumb_Hover"      => "Style the mouse hover state of breadcrumbs here.",
        "Entry_Meta"       => "Entry meta shows information about a post or gallery. Eg, date, author, etc.",
        
        /* Sidebar */
        "Sidebar_Base" => "Common settings for the whole sidebar areas. These can be overwritten by the following sections.",
        "Widget_Title" => "Style the sidebar widget titles here.",
        //"Link" "Style the sidebar links here."
        //"Link hover" "Style the sidebar link mouse hover state here."

        /* Footer */
        "Footer_Base" => "Common settings for the whole footer area. These can be overwritten by the following sections.",
        "Widget_Title" => "Style the footer widget titles here.",
        //"Link" "Style the footer links here."
        //"Link hover" "Style the footer link mouse hover state here."

        /* Galleries */
        "Gallery_Base" => "Common settings for the whole gallery areas. These can be overwritten by the following sections.",
        "Gallery_Title" => "Style the galleries page titles here.",
        "Galleries_Entry_Meta" => "Style the galleries page gallery meta information here.",
        "Gallery_Image_Links" => "Style the gallery images here.",


        /* Layout */
        "Layout_Tab" => "Element spacing settings",
        //"margin" "Create space outside the border of the element."
    	"Margin_Top"    => "(Top)    Specify the amount of space in px ems or % between this element and the element above it",
    	"Margin_Bottom" => "(Bottom) Specify the amount of space in px ems or % between this element and the element below it",
        "Margin_Right"  => "(Right)  Specify the amount of space in px ems or % between this element and the element to the right of it",
        "Margin_Left"   => "(Left)   Specify the amount of space in px ems or % between this element and the element to the left of it",

        //"padding" "Create space within the border of an element."
    	"Padding_Top"    => "(Top)    Specify the amount of space in px ems or % between the top of this element and its content",
    	"Padding_Bottom" => "(Bottom) Specify the amount of space in px ems or % between the bottom of this element and its content",
        "Padding_Right"  => "(Right)  Specify the amount of space in px ems or % between the right edge of this element and its content",
        "Padding_Left"   => "(Left)   Specify the amount of space in px ems or % between the left edge of this element and its content",

    	"Display" => "For more advanced users. Can be used to remove elements or change their positioning.",

        /* Border */
        "Border_Tab" => "Element border settings",
        //"all borders" "Add a border to every side of the element, you can edit the colour, style and thickness of a border."
        "Border_Color" => "Select a color for the entire border of this element",
        "Border_Style" => "Select a style for the entire border of this element",
        "Border_Width" => "Specify a border width in px ems or % for this element",
        //"top border" "Add a border to the top of the element, you can edit the colour, style and thickness of a border."
        "Border_Top_Color" => "Select the color for the top border of this element",
        "Border_Top_Style" => "Select the top border style of this element",
        "Border_Top_Width" => "Specify the top border width in px ems or % of this element",
        //"bottom border" "Add a border to the bottom of the element, you can edit the colour, style and thickness of a border."
        "Border_Bottom_Color" => "Select the color for the bottom border of this element",
        "Border_Bottom_Style" => "Select the bottom border style of this element",
        "Border_Bottom_Width" => "Specify the bottom border width in px ems or % of this element",
        //"left border" "Add a border to the left of the element, you can edit the colour, style and thickness of a border."
        "Border_Left_Color" => "Select the color for the left border of this element",
        "Border_Left_Style" => "Select the left border style of this element",
        "Border_Left_Width" => "Specify the left border width in px ems or % of this element",
        //"right border" "Add a border to the right of the element, you can edit the colour, style and thickness of a border."
        "Border_Right_Color" => "Select the color for the right border of this element",
        "Border_Right_Style" => "Select the right border style of this element",
        "Border_Right_Width" => "Specify the right border width in px ems or % of this element",

	/* Effects */
        "Effect_Tab" => "Additional styling effect settings",
        "Border_Radius" => "Use this to create rounded corners for box type elements. Note - will only work in CSS3 enabled browsers (not Internet Explorer 5-9).",
        //"text shadow" "Enter the horizontal offset, vertical offset, blur and colour of the text shadow. Note - will only work in CSS3 enabled browsers (not Internet Explorer 5-9)."
        //"Box_Shadow" => "Enter the horizontal offset, vertical offset, blur and colour of the box shadow. Note - will only work in CSS3 enabled browsers (not Internet Explorer 5-9).",
        "Box_Shadow_Horizontal"  => "Horizontal offset (px ems or %)",
        "Box_Shadow_Vertical"    => "Vertical offset (px ems or %)",
        "Box_Shadow_Blur_Radius" => "Blur radius (px ems or %)",
        "Box_Shadow_Color"       => "Color",

        "Text_Shadow_Horizontal"  => "Horizontal offset (px ems or %)",
        "Text_Shadow_Vertical"    => "Vertical offset (px ems or %)",
        "Text_Shadow_Blur_Radius" => "Blur radius (px ems or %)",
        "Text_Shadow_Color"       => "Color"
	   );
	/**
	 * returns the relevant tooltip for the given object
	 * @param string $object
	 * @return string tooltip
	 * */
    static public function get($object) {
        $class_name = get_class($object);
        $title = 'no_title';
        if( isset(self::$tooltips[$class_name]) ) $title = self::$tooltips[$class_name];
        return attr_title($title);
    }
}