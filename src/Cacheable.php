<?php

namespace Milax;

use Cache;

trait Cacheable
{
	
	protected static $suffix = '_all';
	protected static $cacheName;
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		static::observeEvents();
	}
	
	/**
	 * Get all cached items of given Eloquent Model.
	 * 
	 * @access public
	 * @static
	 * @return Collection
	 */
	public static function getCached($name = null)
	{
		self::initialize($name);
		
		if (!Cache::has(self::$cacheName))
			self::updateCache();
		
		return Cache::get(self::$cacheName);
	}
	
	/**
	 * Update all given Eloquent Model items in cache.
	 * 
	 * @access public
	 * @static
	 * @return bool
	 */
	public static function updateCache($name = null)
	{
		self::initialize($name);
		Cache::forever(self::$cacheName, static::all());
		return true;
	}
	
	/**
	 * Drop class Caches.
	 * 
	 * @access public
	 * @static
	 * @return bool
	 */
	public static function dropCache($name = null)
	{
		self::initialize($name);
		Cache::forget(self::$cacheName);
		return true;
	}
	
	/**
	 * Initialize class.
	 * 
	 * @access protected
	 * @static
	 * @return void
	 */
	protected static function initialize($name = null)
	{
		if (!is_null($name))
			self::$cacheName = static::class . $name;
		else
			self::$cacheName = static::class . self::$suffix;
	}
	
	/**
	 * Observe model events.
	 * 
	 * @access protected
	 * @static
	 * @return void
	 */
	protected static function observeEvents()
	{
		static::saved(function ($item) {
			static::updateCache();
		});
	}
	
}