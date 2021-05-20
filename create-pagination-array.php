<?php
/*
============================================================================================
Create a simple, abstract paginator based on arguments.
Basically it takes the number of entries and divides them into an array based on the desired
amount to show per page.

============================================================================================
Args:

(REQUIRED)
totalEntries: How many total entries in the paginator

(OPTIONAL)
current: The current page number (must be a positive int)
perPage: How many entries per page (must be a positive int)
limitVisible: Limit the pages visibile in the paginator 
	(good for when there are potentially lots of pages and this will limit it)
	Can be: null, false, true (defaults to 10), or an int
============================================================================================
Returns assoc. array on success or null on failure

(KEYS)
current: Current page number
display-start: When limiting visible pages, the first page displayed in the list
display-end: When limiting visible pages, the last page displayed in the list
display-list: Array of numbers representing pages to be displayed
max: Maximum pages
next: Next page number (NULL if none)
per-page: How many entries per-page
prev: Previous page number (NULL if none)

============================================================================================
*/


class createPaginationArray
{

	var $pagination;
	const DEFAULT_PER_PAGE = 10;
	const DEFAULT_CURRENT_PAGE = 1;
	const DEFAULT_TOTAL_VISIBLE = 10;

	function __construct(
		$totalEntries = null, 
		$current = self::DEFAULT_CURRENT_PAGE, 
		$perPage = self::DEFAULT_PER_PAGE, 
		$limitVisible = null
	)
	{
		
		if($totalEntries === null || !is_numeric($totalEntries) || intval($totalEntries) < 1)
		{
			echo "\r\nBad number of total entries.\r\n";
			return null;
		}

		
		$totalEntries = intval($totalEntries);
		$perPage = intval($perPage) >= 1 ? intval($perPage) : 1;
		$pagination['per-page'] = $perPage;
		$pagination['max'] = intval(ceil($totalEntries / $perPage));

		$current = (intval($current) <= $pagination['max']) ? intval($current) : $pagination['max'];
		$current = (intval($current) >= 1) ? intval($current) : 1;
		$pagination['current'] = $current;

		$pagination['prev'] = ($pagination['current'] - 1 > 0) ? $pagination['current'] - 1 : null;
		$pagination['next'] = ($pagination['current'] + 1 <= $pagination['max']) ? $pagination['current'] + 1 : null;

		if($limitVisible === null || $limitVisible === false)
		{	
			$pagination['display-start'] = 1;
			$pagination['display-end'] = $pagination['max'];
		}
		else
		{
			$limitVisible = ($limitVisible === true) ? self::DEFAULT_TOTAL_VISIBLE : $limitVisible;
			$limitVisible = intval($limitVisible) >= 1 ? intval($limitVisible) : 1;
			$visibleVal1 = floor(($limitVisible - 1) / 2);
			$visibleVal2 = floor($limitVisible - 1);
			$pagination['display-start'] = intval(max(min($pagination['current']-$visibleVal1, $pagination['max']-$visibleVal2), 1));
			$pagination['display-end'] = intval(min($pagination['display-start']+$visibleVal2, $pagination['max']));
		}


		$pagination['display-list'] = array();
		for ($i = $pagination['display-start']; $i <= $pagination['display-end']; $i++) 
		{	array_push($pagination['display-list'],$i);
		}
		if($pagination['max'] <= 1) 
		{	$pagination = array();
		}

		$this->pagination = $pagination;

		return;
	}

	function getPagination()
	{
		return $this->pagination;
	}
}

?>