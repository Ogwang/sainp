<?php
  
Class Google_GajaxAPI {
	
	public $chartPrefix;
	public $chartType;
	public $chartSize;
	public $chartData;
	public $chartLabels;
	
	private $_width;
	private $_height;
	
	function __construct() {
		$this->_setPrefix();
		
		
	}
	
	private function _setPrefix() {
		$this->chartPrefix = array(
									'bhs', //Horizontal bar chart with stacked bars.
									'bvs', //Vertical bar chart with stacked bars.
									'bvo', //Vertical bar chart in which bars are stacked in front of one another
									'bhg', //Horizontal bar charts with grouped bars.
									'bvg', //Horizontal bar charts with grouped bars.
									'gom', //Google-O-Meter chart
									'lc', //A line chart where data points are spaced evenly along the x-axis.
									'ls', //Similar to lc charts, but by default does not display axis lines
									'lxy', //Lets you specify both x- and y-coordinates for each point, rather just the y values
									'p', //Two dimensional pie chart.
									'p3', //A three-dimensional pie chart.
									's', //A scatter chart (or scatter plot)
									);
	}
	
	private function _setSize($width = 250, $height = 100) {
			$this->_width = $width;
			$this->_height = $height;
	}
	
	private function _setData() {
		
	}
	
	
	
}