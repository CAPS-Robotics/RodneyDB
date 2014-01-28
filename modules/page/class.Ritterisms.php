<?php
class Ritterisms {
        public $ritterisms;
        
        public function __construct() {
                $this->ritterisms = [
                  "The beatings will continue until morale improves.",
                  "For every can of soda I find. I'm beating Ozzie up."
                ];
        }

        public function write() {
                $content =
'
<div class="jumbotron">
<p class="lead">' . $this->ritterisms[rand(0, count($this->ritterisms) - 1)] . '</p>
<p style="text-align:right;font-style:italic">&mdash;Daniel Ritter</p>
</div>
';
                echo $content;
        }
}
?>
