<?php
class BroadcastPage extends Page {
	
	public function __construct($trigger, $core) {
		parent::__construct($trigger, $core);
	}

	public function writePageContent() {
        global $core;

?>

<script>
$(function() {
	$("#messageHolder").keyup(function() {
		var charsLeft = (160 - $(this).val().length);
		$("#charCount").html("<span class=\'label label-" + (charsLeft < 25 ? (charsLeft <= 0 ? "danger" : "warning") : "success") + "\'>" + charsLeft + "</span>");
		<?php echo ($core->getUser($_SESSION['email'])['rank'] >= 10 ? '' : 'if(charsLeft < 0){$("#sendBtn").prop("disabled",true);}else{$("#sendBtn").prop("disabled",false);}'); ?>
	});

	$("#sendTestButton").on('click', function(e) {
		$.ajax({
			url: "?p=broadcast",
			type: "POST",
			data: {
				message: $("#messageHolder").val(),
				number: "<?php echo $core->getUser($_SESSION['email'])['phone']; ?>"
			}
		}).done(function(data) {
			alert(data);
		});
	});

	$("#sendFRCButton").on('click', function (e) {
        $.ajax({
            url: "?p=broadcast",
            type: "POST",
            data: {
                frcmessage: $("#messageHolder").val()
            }
        }).done(function (data) {
            alert(data);
        });
        $.ajax({
            url: "http://api.groupme.com/v3/bots/post?token=" + <?php echo GROUPME_TOKEN; ?>,
            type: "POST",
            data: {
                bot_id: "ff52e6dcab3f4c66c11758dff3",
                text: $("#messageHolder").val()
            }
        }).done(function (data) {
            alert(data);
        });
    });

    $("#sendFTCButton").on('click', function (e) {
        $.ajax({
            url: "?p=broadcast",
            type: "POST",
            data: {
                ftcmessage: $("#messageHolder").val()
            }
        }).done(function (data) {
            alert(data);
        });
    });

    $("#sendForm").on('submit', function() {
        $.ajax({
            url: "http://api.groupme.com/v3/bots/post?token=" + <?php echo GROUPME_TOKEN; ?>,
            type: "POST",
            data: {
                bot_id: "ff52e6dcab3f4c66c11758dff3",
                text: $("#messageHolder").val()
            }
        }).done(function (data) {
            alert(data);
        });
    });
});
</script>

<div class="jumbotron" style="font-size: medium;">
	<h1>Broadcast</h1>
	This form will send a SMS message to all members with the receive texts option.
	<form method="POST" id="sendForm">
		<textarea class="form-control" rows="3" id="messageHolder" name="message" placeholder="Message (160 Character limit)"></textarea>
		<span style="margin-top: -30px; z-index: 1; position: relative; float: left; opacity: 0.7;" id="charCount">
			<span class="label label-success">160</span>
		</span>
        <div class="btn-group btn-group-justified">
            <a href="#" id="sendFTCButton" class="btn btn-warning">Send to FTC</a>
            <a href="#" id="sendFRCButton" class="btn btn-primary">Send to FRC</a>
        </div>
		<div class="btn-group btn-group-justified">
			<a href="#" class="btn btn-success" onclick="document.forms['sendForm'].submit();">Send To All</a>
			<a href="#" id="sendTestButton" class="btn btn-default">Send Test to Self</a>
		</div>
	</form>
</div>

<?php

	}

	public function writePage() {
		if (array_key_exists("number", $_POST)) {
			$url = 'http://api.tropo.com/1.0/sessions?action=create&token=' . TROPO_MESSAGE_TOKEN . '&numbers=' . $_POST['number'] . '&msg=' . urlencode($_POST['message']);
			$xml = simplexml_load_file($url);
			if ((string)$xml->success === "true") {
				echo "Success! Test message sent to your number.";
			} else {
				echo "Error! There was a problem with Tropo.";
			}
			return;
		}
        if (array_key_exists("frcmessage", $_POST)) {
            $url = 'http://api.tropo.com/1.0/sessions?action=create&token=' . TROPO_MESSAGE_TOKEN . '&numbers=' . urlencode($this->getFRCNumbers()) . '&msg=' . urlencode($_POST['frcmessage']);
            $xml = simplexml_load_file($url);
            if ((string)$xml->success === "true") {
                echo "Success! Message sent to FRC members.";
            } else {
                echo "Error! There was a problem with Tropo.";
            }
            return;
        }
        if (array_key_exists("ftcmessage", $_POST)) {
            $url = 'http://api.tropo.com/1.0/sessions?action=create&token=' . TROPO_MESSAGE_TOKEN . '&numbers=' . urlencode($this->getFTCNumbers()) . '&msg=' . urlencode($_POST['ftcmessage']);
            $xml = simplexml_load_file($url);
            if ((string)$xml->success === "true") {
                echo "Success! Message sent to FTC members.";
            } else {
                echo "Error! There was a problem with Tropo.";
            }
            return;
        }
		self::writePageStart();
		if (array_key_exists("message", $_POST)) {
			$url = 'http://api.tropo.com/1.0/sessions?action=create&token=' . TROPO_MESSAGE_TOKEN . '&numbers=' . urlencode($this->getFormattedNumbers()) . '&msg=' . urlencode($_POST['message']);
  			$xml = simplexml_load_file($url) or $this->alert("danger", "Error!", "Tropo API not responding.");
  			if ((string)$xml->success === "true") {
  				$this->alert("success", "Yay!", "Message broadcasted to all members.");
  			}
  			else {
  				$this->alert("danger", "Error!", "Tropo error.");
  			}
		}
		self::writePageContent();
		self::writePageEnd();
	}

	private function getFormattedNumbers() {
		global $core;
		$numbersStr = "";
		$teamArr = $core->fetchAllUsers();
		foreach ($teamArr as $member) {
			$numbersStr .= ($member['text'] == 1 ? $member['phone'] . '|' : '');
			if (strlen($member['parentPhones']) != 0) {
				$numbersStr .= $member['parentPhones'] . '|';
			}
		}
		$numbersStr = substr($numbersStr, 0, strlen($numbersStr) - 1);
		return $numbersStr;
	}

    private function getFRCNumbers() {
        global $core;
        $numbersStr = "";
        $teamArr = $core->fetchFRCUsers();
        foreach ($teamArr as $member) {
            $numbersStr .= ($member['text'] == 1 ? $member['phone'] . '|' : '');
        }
        $numbersStr = substr($numbersStr, 0, strlen($numbersStr) - 1);
        return $numbersStr;
    }

    private function getFTCNumbers() {
        global $core;
        $numbersStr = "";
        $teamArr = $core->fetchFTCUsers();
        foreach ($teamArr as $member) {
            $numbersStr .= ($member['text'] == 1 ? $member['phone'] . '|' : '');
        }
        $numbersStr = substr($numbersStr, 0, strlen($numbersStr) - 1);
        return $numbersStr;
    }
}
?>
