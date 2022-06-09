<?php

class HTMLParser
{
	private $file;
	private $html;
	private $tree = [];

	/**
	 * @throws ErrorException
	 */
	public function __construct(string $html, bool $file = true)
	{
		if ($file === true) {
			$this->file = $html;
			if (!file_exists($this->file)) {
				throw new Error("File $this->file does not exist!");
			}
			if (!is_readable($this->file)) {
				throw new Error("File $this->file is not readable!");
			}
			if (!$this->count_tags(file_get_contents($this->file))) {
				throw new Error("One or more tags are not closed");
			}
		} else {
			$this->html = $html;
			if (!$this->count_tags($this->html)) {
				throw new Error("HTML is invalid!");
			}
		}
		$this->parse();
	}

	private function parse(): void
	{
		if ($this->file) {
			$document = fopen($this->file, 'rb');
			while (($buffer = fgets($document)) !== false) {
				$this->parse_line($buffer);
			}
		} else {
			$lines = explode(PHP_EOL, $this->html);
			foreach ($lines as $line) {
				$this->parse_line();
			}
		}
	}

	/**
	 * @param $line
	 *
	 * @return void
	 */
	private function parse_line($line): void
	{
		$line = trim($line);
		preg_match('/<(.*?)\/?>((.*?)<\/(.*?)>)?/', $line, $node);
		if (count($node)) {
			$attrs = [];
			$tag = null;
			if (str_starts_with($node[0], '<!')) {
				return;
			}
			if (isset($node[1])) {
				if (str_contains($node[1], '/')) {
					return;
				}
				if (str_contains($node[1], ' ') === false) {
					$tag = $node[1];
				} else {
					$segments = explode(' ', $node[1]);
					$segment_count = count($segments);
					for ($i = 0; $i < $segment_count; $i++) {
						if ($i === 0) {
							$tag = $segments[$i];
							continue;
						}
						if (str_contains($segments[$i], '=')) {
							$attr = explode('=', $segments[$i]);
							$key = $attr[0];
							$value = trim($attr[1], "'\"");
							$attrs[$key] = $value;
						} else {
							$attrs[$segments[$i]] = true;
						}
					}
				}
			}
			if (isset($node[3]) && $node[3]) {
				$attrs['inner'] = $node[3];
			}
			if ($tag !== null) {
				$this->tree[] = [$tag => $attrs];
			}
		}
	}

	private function count_tags($html): bool
	{
		$void_tags = ['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
		$match_open_tags = '/<(?!' . implode('|', $void_tags) . ')[^\/!].*?>/';
		$match_closed_tags = '/<\/(.*?)>/';
		preg_match_all($match_open_tags, $html, $open_tags);
		preg_match_all($match_closed_tags, $html, $closed_tags);
		return count($open_tags[0]) === count($closed_tags[0]);
	}

	public function tree(): bool|array
	{
		if (count($this->tree)) {
			return $this->tree;
		}
		return false;
	}

	public function print_tree(): void
	{
		print_r($this->tree);
	}

	public function printf_tree(): void
	{
		echo '<pre>' . print_r($this->tree, true) . '</pre>';
	}
}