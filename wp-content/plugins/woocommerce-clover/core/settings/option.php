<?php

namespace Wooclover\Core\Settings;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Option extends \Wooclover\Core\Domain\DomainObject {

	private $name;
	private $label;
	private $type;
	private $value;
	private $defaultValue;
	private $group;
	private $private = false;
	private $required = false;
	private $options = array();
	private $helpMessage;

	/**
	 *
	 * @var \Maven\Core\UI\OptionOutputGenerator
	 */
	private $outputGenerator;

	/**
	 * 
	 * @param type $name
	 * @param type $label
	 * @param type $value
	 * @param type $defaultValue
	 * @param \Maven\Settings\OptionType $type
	 * @param type $private
	 * @param type $required
	 * @param type $group
	 * * @param \Maven\Core\UI\OptionOutputGenerator $outputGenerator Object that deal with how the option will be render. By default it use \Maven\Core\UI\DefaultOptionOutputGenerator
	 */
	public function __construct( $name, $label, $value = "", $defaultValue = "", $type = OptionType::Input, $private = false, $required = false, $helpMessage = "", $group = "General", $outputGenerator = null ) {

		$this->setId( $name );
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
		$this->value = $value;
		$this->defaultValue = $defaultValue;
		$this->group = $group;
		$this->private = $private;
		$this->required = $required;
		$this->helpMessage = $helpMessage;
	}

	public function getDefaultValue() {
		return $this->defaultValue;
	}

	public function setDefaultValue( $value ) {
		$this->defaultValue = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function getLabel() {
		return $this->label;
	}

	public function setLabel( $value ) {
		$this->label = $value;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $value ) {
		$this->name = $value;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $value ) {
		$this->type = $value;
	}

	public function getGroup() {
		return $this->group;
	}

	public function setGroup( $group ) {
		$this->group = $group;
	}

	public function render() {
		$this->outputGenerator->render();
	}

	public function getRenderedCode() {
		return $this->outputGenerator->getRenderedCode();
	}

	public function isPrivate() {
		return $this->private;
	}

	public function getOutputGenerator() {
		return $this->outputGenerator;
	}

	public function setPrivate( $private ) {
		$this->private = $private;
	}

	public function setOutputGenerator( \Maven\Core\UI\OptionOutputGenerator $outputGenerator ) {
		$this->outputGenerator = $outputGenerator;
	}

	public function isRequired() {
		return $this->required;
	}

	public function setRequired( $required ) {
		$this->required = $required;
	}

	public function getOptions() {
		return $this->options;
	}

	public function setOptions( $options ) {
		$this->options = $options;
	}

	public function getHelpMessage() {
		return $this->helpMessage;
	}

	public function setHelpMessage( $helpMessage ) {
		$this->helpMessage = $helpMessage;
	}

}

class OptionType {

	const Input = 'input';
	const TextArea = 'textarea';
	const Password = 'password';
	const DropDown = 'dropdown';
	const WPDropDownPages = 'wpdropdownpages';
	const WPEditor = 'wpeditor';
	const ReadOnly = 'readonly';
	const CheckBox = 'checkbox';
	const Email = 'email';

}
