{"version":3,"file":"logger.bundle.map.js","names":["this","BX","Messenger","exports","ownKeys","object","enumerableOnly","keys","Object","getOwnPropertySymbols","symbols","filter","sym","getOwnPropertyDescriptor","enumerable","push","apply","_objectSpread","target","i","arguments","length","source","forEach","key","babelHelpers","defineProperty","getOwnPropertyDescriptors","defineProperties","_classPrivateFieldInitSpec","obj","privateMap","value","_checkPrivateRedeclaration","set","privateCollection","has","TypeError","_types","WeakMap","_config","_custom","Logger","classCallCheck","writable","classPrivateFieldSet","desktop","log","info","warn","error","trace","classPrivateFieldGet","__load","createClass","setConfig","types","type","hasOwnProperty","enable","__save","disable","isEnabled","_console","_len","params","Array","_key","console","concat","toConsumableArray","__getStyles","_console2","_len2","_key2","_console3","_len3","_key3","_console4","_len4","_key4","_console5","_len5","_key5","_console6","window","localStorage","custom","JSON","stringify","setItem","e","getItem","parse","undefined","styles","__getRemoveString","result","logger","Lib"],"sources":["logger.bundle.js"],"mappings":"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"}