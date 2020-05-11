/**
 * [StorageException description]
 * @param {[type]} message [description]
 */
function StorageException(message) {
  this.message = message;
  this.name = 'StorageException';
}



/**
 * Storage API helper method that sets a key/value in specified storage API with
 *   an optional cache duration in minutes
 * @private
 * @sauce  https://developer.mozilla.org/en-US/docs/Web/API/Storage/setItem
 *
 * @param  {object}   StorageAPI          Storage API object to use for .getItem() calls
 * @param  {string}   key                 A DOMString containing the name of the key you want to create/update
 * @param  {any}      value               The value you want to give the key you are creating/updating
 * @param  {integer}  durationInMinutes   The time in minutes the item should be valid for, used for passively expiring cache
 * @return {any}                          The initial value data
 */
function _setItem(StorageAPI, key, value, durationInMinutes=null, readonly=false) {

  const MINUTES = 1000 * 60

  // ensure any value plugged into readonly is boolean
  readonly = !!readonly

  // determine
  let result = JSON.parse(StorageAPI.getItem(key))

  if (result && result.readonly) {
    throw new StorageException(key, 'is a readonly entry')
    return null
  }

  // ensure the cache duration is an integer
  durationInMinutes = parseInt(durationInMinutes, 10)

  if (isNaN(durationInMinutes)) {
    durationInMinutes = -1
  }

  // if durationInMinutes is greater than 0 minutes, calc the expiration time in microseconds
  let expires = durationInMinutes > 0
    ? new Date().getTime() + (Math.abs(durationInMinutes) * MINUTES)
    : null

  // NOTE: this uses ES6 Object property shorthand, modify as necessary
  // @sauce: https://alligator.io/js/object-property-shorthand-es6/

  let data = JSON.stringify({
    expires,
    value,
    readonly,
  })

  StorageAPI.setItem(key, data)

  return value
}



function _setReadOnlyItem(StorageAPI, key, value, durationInMinutes) {
  return _setItem(StorageAPI, key, value, durationInMinutes, true)
}


/**
 * Storage API helper method to verify item cache before returning the desired data
 * @private
 * @sauce  https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
 *
 * @param  {object}   StorageAPI  Storage API object to use for .getItem() calls
 * @param  {string}   key         A DOMString containing the name of the key you want to create/update
 * @return {boolean}  false       If the storage item does not exist, or is expired, returns false
 * @return {any}      value       If the storage item exists and is not expired, returns the stored data
 */
function _getItem(StorageAPI, key) {
  let data = JSON.parse(StorageAPI.getItem(key))

  // check if storage key value exists
  if (!data) {
    return false
  }

  // check if storage key is expired
  if (data.expires && data.expires < new Date().getTime()) {
    return false
  }

  return data.value
}



/**
 * Alias of storage.removeItem method
 * @private
 * @sauce  https://developer.mozilla.org/en-US/docs/Web/API/Storage/removeItem
 *
 * @param  {object}   StorageAPI  The storage API object to be used (eg: localStorage, sessionStorage)
 * @param  {string}   key         A DOMString containing the name of the key you want to delete
 * @return {boolean}              Returns true or false if the item was successfully deleted
 */
function _deleteItem(StorageAPI, key) {
  StorageAPI.removeItem(key)

  return StorageAPI.getItem(key) === null ? true : false
}


/**
 * Alias of storage.removeItem method
 * @private
 * @sauce  https://developer.mozilla.org/en-US/docs/Web/API/Storage/removeItem
 *
 * @param  {object}   StorageAPI  The storage API object to be used (eg: localStorage, sessionStorage)
 * @return {null}
 */
function _deleteAll(StorageAPI) {
  StorageAPI.clear()
}



/**
 * Invalidates the specified StorageAPI cache item
 * @private
 * @sauce  https://developer.mozilla.org/en-US/docs/Web/API/Storage/removeItem
 *
 * @param  {object}   StorageAPI  The storage API object to be used (eg: localStorage, sessionStorage)
 * @param  {string}   key         A DOMString containing the name of the key you want to delete
 * @return {null}                 Returns false as the item cache has now been invalidated
 */
function _invalidateItem(StorageAPI, key) {
  return _setItem(StorageAPI, key, false)
}



/**
 * Enumerates and Invalidates all StorageAPI indexes
 * @private
 *
 * @param  {object}   StorageAPI  The storage API object to be used (eg: localStorage, sessionStorage)
 * @return {null}                 Returns false as the item cache has now been invalidated
 */
function _invalidateAll(StorageAPI) {
  for (var i = StorageAPI.length - 1; i >= 0; i--) {
    _invalidateItem(StorageAPI, StorageAPI.key(i))
  }
}



/**
 * Enumerates and Invalidates all StorageAPI indexes that match a given string or regexp
 * @private
 *
 * @param  {object}         StorageAPI  The storage API object to be used (eg: localStorage, sessionStorage)
 * @param  {regexp|string}  key         A regexp that describes the key label format we wish to invalidate
 * @return {null}
 */
function _invalidateItems(StorageAPI, key) {
  const IS_REGEXP = !typeof(key) === 'object' && !key.test
  const IS_STRING = typeof(key) === 'string'

  for (var i = StorageAPI.length - 1; i >= 0; i--) {
    indexKey = StorageAPI.key(i)

    if ((IS_REGEXP && key.test(indexKey))
    ||  (IS_STRING && key === indexKey)
    ) {
      _invalidateItem(StorageAPI, indexKey)
    }
  }
}



/**
 * Storage aliases of StorageAPI.setItem() with optional cache duration in minutes
 * @param  {string}   key               A DOMString containing the name of the key you want to create/update
 * @param  {any}      value             The value you want to give the key you are creating/updating
 * @param  {integer}  durationInMinutes An Integer of time in minutes you wish this value to be valid for
 * @return {any}                        The initial value data
 */
export function lsSetItem(key, value, durationInMinutes = 0) { return _setItem(localStorage, key, value, durationInMinutes) }
export function ssSetItem(key, value, durationInMinutes = 0) { return _setItem(sessionStorage, key, value, durationInMinutes) }



/**
 * Storage aliases of StorageAPI.getItem()
 * @param  {string}   key     A DOMString containing the name of the key you want to recall
 * @return {any}              The initial value data
 */
export function lsGetItem(key) { return _getItem(localStorage, key) }
export function ssGetItem(key) { return _getItem(sessionStorage, key) }



/**
 * Storage macro of _setItem to invalidate cached item
 * @param  {string}   key     A DOMString containing the name of the key you want to delete
 * @return {boolean}          True if the deletion was successful
 */
export function lsInvalidateItem(key) { return _invalidateItem(localStorage, key) }
export function ssInvalidateItem(key) { return _invalidateItem(sessionStorage, key) }



/**
 * Storage macro of _setItem to invalidate cached item(s) that match a given regexp
 * @param  {regexp}   key     A DOMString containing the name of the key you want to delete
 * @return {boolean}          True if the deletion was successful
 */
export function lsInvalidateItems(key) { return _invalidateItems(localStorage, key) }
export function ssInvalidateItems(key) { return _invalidateItems(sessionStorage, key) }



/**
 * Storage aliases of StorageAPI.removeItem()
 * @param  {string}   key     A DOMString containing the name of the key you want to delete
 * @return {boolean}          True if the deletion was successful
 */
export function lsDeleteItem(key) { return _deleteItem(localStorage, key) }
export function ssDeleteItem(key) { return _deleteItem(sessionStorage, key) }



/**
 * Storage aliases of StorageAPI.clear()
 * @param  {string}   key     A DOMString containing the name of the key you want to delete
 * @return {boolean}          True if the deletion was successful
 */
export function lsDeleteAll() { return _deleteAll(localStorage) }
export function ssDeleteAll() { return _deleteAll(sessionStorage) }
