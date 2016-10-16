<?php
    class Cascade extends Modules {
        static function __install() {
            Config::current()->set("ajax_scroll_auto", true);
        }

        static function __uninstall() {
            Config::current()->remove("ajax_scroll_auto");
        }

        public function admin_cascade_settings($admin) {
            if (!Visitor::current()->group->can("change_settings"))
                show_403(__("Access Denied"), __("You do not have sufficient privileges to change settings."));
    
            if (empty($_POST))
                return $admin->display("pages".DIR."cascade_settings");
    
            if (!isset($_POST['hash']) or $_POST['hash'] != token($_SERVER["REMOTE_ADDR"]))
                show_403(__("Access Denied"), __("Invalid security key."));
    
            Config::current()->set("ajax_scroll_auto", isset($_POST['auto']));
            Flash::notice(__("Settings updated."), "/?action=cascade_settings");
        }

        public function settings_nav($navs) {
            if (Visitor::current()->group->can("change_settings"))
                $navs["cascade_settings"] = array("title" => __("Cascade", "cascade"));

            return $navs;
        }

        public function javascript() {
            include MODULES_DIR.DIR."cascade".DIR."javascript.php";
        }
    }
