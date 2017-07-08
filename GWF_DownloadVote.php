<?php
final class GWF_DownloadVote extends GWF_VoteTable
{
	public function gdoVoteObjectTable() { return GWF_Download::table(); }
}
