<?php

class HTMLParser
{
	private $file;
//	private $exclude = ['doctype html'];
	private $tree = [];
	private $open_nodes = [];

	private $matching_tags = [];

	public function __construct($file)
	{
		$this->file = $file;
		$this->parse();
//		if(substr($buffer,0,2) === "</") {
//			continue;
//		}
		print_r($this->matching_tags);
	}

	private function parse() {
		$this->get_tags();
		foreach($this->matching_tags as $tag) {

		}
	}

	private function get_tags()
	{
		$document = fopen($this->file, "rb");
		while (($buffer = fgets($document)) !== false) {
			// Ignore document declaration
			if (trim(strtolower($buffer)) === "<!doctype html>") {
				continue;
			}
			$line = trim($buffer);
			preg_match('#(<(.*?)>|<(.*?)>(.*?)</(.*?)>)#', $line, $matches);
//			// Does this chunk have a tag?
//			if (str_contains($buffer, "<") && str_contains($buffer, ">")) {
//				// Is this a closing tag?
//				$line = trim($buffer);
//				if (substr($line, 0, 2) === "</") {
////					$this->finish_node();
//
//				} else {
//					preg_match('#<(.*?)>(.*?)#', $line, $matches);
////					$this->start_node($line);
//				}
//			}
			$this->matching_tags[] = $matches;
		}
	}

	private function start_node(string $buffer)
	{
		$node = [];
		preg_match('#<\/?(.*?)>#', trim($buffer), $matches);
		if (!$matches[1]) {
			return;
		}
		$attributes = explode(" ", $matches[1]);
		$i = 0;
		foreach ($attributes as $attribute) {
			if ($i !== 0) {
				$kv = explode("=", $attribute);
				$node[$kv[0]] = ($kv[1]) ? trim($kv[1], '"') : null;
			} else {
				$tag = $attribute;
			}
			++$i;
		}
		// Get inner text...
//		$inner_text = preg_match("#>(.*?)<#", trim($buffer), $matches);
//		if (isset($matches[1])) {
//			$node['inner_text'] = $matches[1];
//		}
		if (!isset($node['class']) && !isset($node['id'])) {
			$tag .= "-" . uniqid();
		} else {
			if (isset($node['class'])) {
				$tag .= ".{$node['class']}";
			}
			if (isset($node['id'])) {
				$tag .= "#{$node['id']}";
			}
		}
//		$this->open_nodes[$tag] = [$node];
	}

	private function finish_node()
	{
//		$tag = $this->current_node[0];
//		$node = $this->current_node[1];
//		$this->tree[$tag] = $node;
	}

	public function print_tree()
	{
//		echo "<pre>" . print_r($this->tree, true) . "</pre>";
	}
}