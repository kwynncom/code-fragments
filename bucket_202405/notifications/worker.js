// be very careful to un-register as needed; it is hard to refresh these
// the standard debugger and then application allows one to unregister
// or about:debugging#/runtime/this-firefox
self.addEventListener('install', function(event) {
  self.console.log('installed');
  
});

self.addEventListener('push', function(event) {
	console.log('push received');
	self.registration.showNotification('0439-1'); 		
});
