<?php
/**
 VinciPlanning (https://github.com/Kysic/vinciplanning/)

 Application web de gestion de planning destinée à l'association
 Vinci-Codex : http://www.vincicodex.com/

 Copyright (C) 2013 Ludovic PLANTIN (http://kysicurl.free.fr/contact)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
abstract class FormEntry
{
	const FIELD_CONTENT_VALID = 0;
	const FIELD_REQUIRED_NOT_SET = -1;
	const FIELD_CONTENT_SIZE_UNDER_LIMIT = -2;
	const FIELD_CONTENT_SIZE_OVER_LIMIT = -3;
	const FIELD_CONTENT_FORMAT_INVALID = -4;

	private $_label;
	private $_inputType;
	private $_expectedFormat;
	private $_required;
	private $_minSize;
	private $_maxSize;
	
	public function getLabel() {
		return $this->_label;
	}
	
	public function getInputType() {
		return $this->_inputType;
	}
	
	public function getExpectedFormat() {
		return $this->_expectedFormat;
	}
	
	public function isRequired() {
		return $this->_required;
	}
	
	public function getMinSize() {
		return $this->_minSize;
	}
	
	public function getMaxSize() {
		return $this->_maxSize;
	}
	
	abstract public function isValid($data);
	
	public function __construct(
			$label,
			$inputType,
			$expectedFormat,
			$required,
			$minSize,
			$maxSize) {
		$this->_label = $label;
		$this->_inputType = $inputType;
		$this->_expectedFormat = $expectedFormat;
		$this->_required = $required;
		$this->_minSize = $minSize;
		$this->_maxSize = $maxSize;	
	}
}

class GenericFormEntry extends FormEntry {

	private $_filter;
	private $_filterOptions;
	
	public function __construct(
			$label,
			$inputType,
			$expectedFormat,
			$required,
			$minSize,
			$maxSize,
			$filter,
			$filterOptions = array()) {
		parent::__construct($label, $inputType, $expectedFormat, $required, $minSize, $maxSize);
		$this->_filter = $filter;
		$this->_filterOptions = $filterOptions;
	}
	
	public function isValid($data) {
		if (empty($data)) {
			return $this->isRequired() ? FormEntry::FIELD_REQUIRED_NOT_SET : FormEntry::FIELD_CONTENT_VALID;
		}
		if (strlen($data) < $this->getMinSize()) {
			return FormEntry::FIELD_CONTENT_SIZE_UNDER_LIMIT;
		}
		if ($this->getMaxSize() > 0 && strlen($data) > $this->getMaxSize()) {
			return FormEntry::FIELD_CONTENT_SIZE_OVER_LIMIT;
		}
		if (filter_var($data, $this->_filter, $this->_filterOptions)) {
			return FormEntry::FIELD_CONTENT_VALID;
		}
		return FormEntry::FIELD_CONTENT_FORMAT_INVALID;
	}
}

class FormVerificationEntry extends FormEntry {
	
	private $_expectedValue;
	
	public function __construct(
			$label,
			$inputType,
			$expectedFormat,
			$required,
			$minSize,
			$maxSize,
			$expectedValue) {
		parent::__construct($label, $inputType, $expectedFormat, $required, $minSize, $maxSize);
		$this->_expectedValue = $expectedValue;
	}
	
	public function isValid($data) {
		if ($data == $this->_expectedValue) {
			return FormEntry::FIELD_CONTENT_VALID;
		}
		return FormEntry::FIELD_CONTENT_FORMAT_INVALID;
	}
	
}

?>