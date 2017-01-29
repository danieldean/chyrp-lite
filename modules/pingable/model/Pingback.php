<?php
    /**
     * Class: Pingback
     * The model for the Pingbacks SQL table.
     *
     * See Also:
     *     <Model>
     */
    class Pingback extends Model {
        public $belongs_to = "post";

        /**
         * Function: __construct
         * See Also:
         *     <Model::grab>
         */
        public function __construct($pingback_id, $options = array()) {
            parent::grab($this, $pingback_id, $options);

            if ($this->no_results)
                return false;
        }

        /**
         * Function: find
         * See Also:
         *     <Model::search>
         */
        static function find($options = array(), $options_for_object = array()) {
            return parent::search(get_class(), $options, $options_for_object);
        }

        /**
         * Function: add
         * Adds a pingback to the database.
         *
         * Parameters:
         *     $post_id - The ID of our blog post that was pinged.
         *     $source - The URL of the blog post that pinged us.
         *     $title - The title of the blog post that pinged us.
         *     $created_at - The pingback creation date (optional).
         */
        static function add($post_id, $source, $title, $created_at = null) {
            $sql = SQL::current();

            $sql->insert("pingbacks",
                         array("post_id"    => $post_id,
                               "source"     => $source,
                               "title"      => strip_tags($title),
                               "created_at" => oneof($created_at, datetime())));

            $new = new self($sql->latest("pingbacks"));
            Trigger::current()->call("add_pingback", $new);
            return $new;
        }

        /**
         * Function: delete
         * Deletes a pingback from the database.
         *
         * See Also:
         *     <Model::destroy>
         */
        static function delete($pingback_id) {
            parent::destroy(get_class(), $pingback_id);
        }

        /**
         * Function: install
         * Creates the database table.
         */
        static function install() {
            SQL::current()->query("CREATE TABLE IF NOT EXISTS __pingbacks (
                                       id INTEGER PRIMARY KEY AUTO_INCREMENT,
                                       post_id INTEGER NOT NULL,
                                       source VARCHAR(2048) DEFAULT '',
                                       title LONGTEXT,
                                       created_at DATETIME DEFAULT NULL
                                   ) DEFAULT CHARSET=utf8");
        }

        /**
         * Function: uninstall
         * Drops the database table.
         */
        static function uninstall() {
            SQL::current()->query("DROP TABLE __pingbacks");
        }
    }
