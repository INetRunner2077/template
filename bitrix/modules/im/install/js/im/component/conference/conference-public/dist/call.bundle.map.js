{"version":3,"sources":["call.bundle.js"],"names":["this","BX","exports","ui_dialogs_messagebox","im_mixin","main_core_events","im_component_dialog","im_component_textarea","ui_switcher","ui_vue_components_smiles","main_core","im_lib_logger","ui_forms","ui_vue","im_const","im_lib_cookie","im_lib_utils","ui_vue_vuex","Vue","cloneComponent","methods","hideForm","event","$parent","hideSmiles","template","MicLevel","props","data","bars","barDisabledColor","barEnabledColor","watch","localStream","stream","Type","isNil","startAudioCheck","mounted","babelHelpers","toConsumableArray","document","querySelectorAll","computed","localize","getFilteredPhrases","$root","$bitrixMessages","audioContext","window","AudioContext","webkitAudioContext","analyser","createAnalyser","microphone","createMediaStreamSource","scriptNode","createScriptProcessor","smoothingTimeConstant","fftSize","connect","destination","onaudioprocess","processVolume","_this","arr","Uint8Array","frequencyBinCount","getByteFrequencyData","values","i","length","average","oneBarValue","barsToColor","Math","round","elementsToColor","slice","forEach","elem","style","backgroundColor","CheckDevices","noVideo","selectedCamera","selectedMic","mediaStream","showMic","userDisabledCamera","gettingVideo","created","$on","state","onCameraStateChange","onMicStateChange","stopLocalVideo","cameraId","onCameraSelected","micId","onMicSelected","getDefaultDevices","destroyed","noVideoText","_this2","constraints","audio","video","Utils","device","isMobile","width","ideal","height","Call","Hardware","defaultCamera","deviceId","exact","Object","keys","cameraList","defaultMicrophone","navigator","mediaDevices","getUserMedia","then","setLocalStream","getVideoTracks","getSettings","playLocalVideo","getApplication","setSelectedCamera","getAudioTracks","setSelectedMic","catch","e","Logger","warn","getLocalStream","_this3","error","setCameraState","setLocalVideoStream","$refs","volume","srcObject","play","getTracks","tr","stop","stopLocalVideoStream","$bitrixApplication","components","ErrorComponent","downloadAppArticleCode","objectSpread","bitrix24only","errorCode","CallApplicationErrorCode","detectIntranetUser","userLimitReached","kickedFromCall","wrongAlias","conferenceFinished","finished","unsupportedBrowser","missingMicrophone","unsafeConnection","noSignalFromCamera","CallErrorCode","userLeftCall","Vuex","mapState","callApplication","reloadPage","location","reload","redirectToAuthorize","href","origin","pathname","continueAsGuest","Cookie","set","concat","common","alias","path","getBxLink","getAlias","openHelpArticle","Helper","show","OrientationDisabled","freeze","BX_IM_COMPONENT_CALL_ROTATE_DEVICE","popupModes","preparation","component","mixins","DialogCore","TextareaCore","DialogReadMessages","userNewName","password","checkingPassword","wrongPassword","permissionsRequested","waitingForStart","popupMode","viewPortMetaNode","conferenceDuration","durationInterval","EventEmitter","subscribe","EventType","conference","initCompleted","initUploader","setMobileMeta","body","classList","add","isDesktop","addEventListener","onBeforeUnload","bind","isHttps","setError","passwordChecked","focus","clearInterval","isChatShowed","newValue","$nextTick","emit","dialog","scrollToBottom","textarea","externalFocus","dialogInited","setDialogInited","conferenceStarted","setInterval","updateConferenceDuration","userInited","requestPermissions","userId","application","conferenceTitle","conferenceStartDate","conferenceStatusClasses","classes","push","conferenceStatusText","intranetAvatarStyle","user","extranet","avatar","backgroundImage","init","dialogName","name","dialogCounter","counter","publicLink","public","link","inited","userHasRealName","showChat","userCounter","userInCallCounter","userInCallCount","isPreparationStep","CallStateType","passChecked","mobileDisabled","type","DeviceType","mobile","userAgent","toString","includes","orientation","DeviceOrientation","horizontal","typeof","screen","availHeight","logoutLink","bitrix_sessid","users","collection","dialogues","dialogId","setNewName","setUserName","trim","startCall","toggleSmiles","checkPassword","checkResult","finally","initHardware","MessageBox","message","modal","buttons","MessageBoxButtons","OK","startConference","_ref","joinConference","_ref2","setUserReadyToJoin","setJoinType","openChat","toggleChat","addMessageWithFile","stopWriting","chatId","uploader","senderOptions","customHeaders","getUserHash","addTask","taskId","file","id","fileData","source","fileName","generateUniqueName","diskFolderId","previewBlob","onCloseChat","onTextareaAppButtonClick","appId","callView","preventDefault","returnValue","onSmilesSelectSmile","insertText","text","onSmilesSelectSet","platform","isBitrixDesktop","createElement","setAttribute","head","appendChild","browser","isSafariBased","startDate","currentDate","Date","durationInSeconds","floor","minutes","seconds","protocol","$store","hash","Messenger","UI","Dialogs","Mixin","Event","Lib","Const"],"mappings":"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","file":"call.bundle.map.js"}