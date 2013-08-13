<?php
//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

$definedRanks = array(
 100 => "Administrator",
 90 => "Team Leaders",
 60 => "Team Member++",//Team members who have enough hours to go to FIRST or are key members of the team.
 50 => "Team Member",
 25 => "Unconfirmed User",
 0 => "Guest",
);
$specialRanks = array(
 51 => "Team Member (PR)",
 65 => "Mentor",//Not using 55 because mentors do not receive the 50 hours restriction and do not have to log hours at all.
 61 => "Team Member++ (PR)",
);

function canViewTasks($rank) {return $rank>0;}
function canViewMeetings($rank) {return $rank>=50;}
function canScheduleMeetings($rank) {return $rank>=90;}
function canMakeAnnouncements($rank) {return $rank>=90;}
function canViewTeamDirectory($rank) {return $rank>=50;}
function canViewTeamDirectory2($rank) {return $rank>=90 || $rank==51 || $rank==61 || $rank==65;}
function canStartCheckin($rank) {return $rank>=90;}
function canViewRecentActivity($rank) {return $rank>=50;}
?>