<?php

namespace Milax;

use Cache;

trait Cacheable
{
	
	protected static $suffix = '_all';
	protected static $cacheName;
	
	/**
	 * Get all cached items of given Eloquent Model.
	 * 
	 * @access public
	 * @static
	 * @return Collection
	 */
	public static function getCached()
	{
		self::initialize();
		
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
	public static function updateCache()
	{
		self::initialize();
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
	public static function dropCache()
	{
		self::initialize();
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
	protected static function initialize()
	{
		self::$cacheName = static::class . self::$suffix;
	}
	
}