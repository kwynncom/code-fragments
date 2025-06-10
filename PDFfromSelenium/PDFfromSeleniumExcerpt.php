<?php
/* In Selenium, assume you're programmatically using / driving a site that upon a button click (or whatever) opens a new tab with a PDF.  This is how you extract the PDF.  I'm copying and pasting from working code, but I may of course make a mistake. Much of this comes from Grok 3.0 in early May, 2025.  */

class extractPDFfromSelenium {

private function mainIsh() {
    $originalWindowOrTab = $this->clickToInitiatePDF(); // tab is more correct than window from a standard English point of view
    $this->doDownload($originalWindowOrTab);
}

private function clickToInitiatePDF() {

    // I'm assuming you have a Selenium $driver instantiated.

    $actions = new WebDriverActions($driver);

    // click the button that opens a new tab that eventually has a PDF
    $actions->moveToElement('buttonHTMLElementID')->click()->perform(); 
    $originalWindow = $driver->getWindowHandle(); // save the tab that just clicked the button
    $this->doPDFtab($originalWindow);
}

private function doPDFtab($originalWindow) {

    $driver = $this->driver;

    // If you work hard enough, you can get probably rid of sleeps, but better to be safe than sorry.
    sleep(3);

    // 10 is a timeout of 10s.  By default, the wait polls every 0.5s, at least that's what Grok says, and it's plausible
    $wait = new WebDriverWait($driver, 10);

    // First, you're waiting for the new tab to open.  This assume you only have one tab open so far.
    $wait->until(function () use ($driver, $originalWindow) {
	return count($driver->getWindowHandles()) > 1;
    });

    $windowHandles = $driver->getWindowHandles();
    foreach ($windowHandles as $handle) {
	if ($handle !== $originalWindow) {
	    $driver->switchTo()->window($handle); // switch control to the new tab
	    break;
	}
    }

/* Wait until the new tab opens to the point that a PDF object exists.  I figured this out almost entirely on my own.  Grok didn't think of waiting for these conditions.  If you don't do the following, you have to guess at a sleep time. 

Note: Due to a bug in JS from decades ago, null is considered an object in JS, or so Grok says.  Grok didn't write this, but it added
that comment when I asked its opinion.
*/
    	$wait = new WebDriverWait($driver, 60, 1000);
	$wait->until(function () use ($driver) {
	    // $s must be inside function
	    $s = <<<PRS213
		return (
			          document.readyState			  === 'complete'
			&& typeof window.PDFViewerApplication		  === 'object'
			&&	  window.PDFViewerApplication		  !== null
			&& typeof window.PDFViewerApplication.pdfDocument === 'object'
			&&	  window.PDFViewerApplication.pdfDocument !== null

		    );		
PRS213;
	    $ret = $driver->executeScript($s) === true;
	    return $ret;
	});
    } // 

private function doDownload(mixed $orwin) { // orwin is original window.  I think it's a string type, but not sure enough.


    sleep(3);

    $this->driver->manage()->timeouts()->setScriptTimeout(60); // just to be sure
    $b64 = $this->driver->executeScript($this->getJSExtractCode());
    $pdf = base64_decode($b64); unset($b64);
    $this->savePDF($pdf); // savePDF is essentially file_put_contents('filename.pdf', $pdf);
    $this->driver->switchTo()->window($orwin); // switch Selenium control back to the calling tab
}

private function getJSExtractCode() : string {

// This is Grok's creation.  It looks like the logic goes from text (UNICODE?) to binary to text.  There may be a better way, but I 
// haven't dug at this yet.  One problem is that data is an array (not sure of what), and returning binary does NOT work.

    $jsec = <<<JS
	return (async () => {
	    console.log('Starting PDF data fetch');
	    if (   !window.PDFViewerApplication 
		|| !window.PDFViewerApplication.pdfDocument)  throw new Error('pdfDocument err #001979');

	    const data = await window.PDFViewerApplication.pdfDocument.getData();
	    console.log('PDF data received, length: ' + data.length + ' bytes');
	    let binary = '';
	    for (let i = 0; i < data.length; i++) {

		       // String.fromCharCode decodes from UNICODE
		binary += String.fromCharCode(data[i]);
		if ((i + 1) % 500000 === 0) console.log('Processed ' + (i + 1) + ' bytes of PDF data');
	    }
	    
	    // btoa (binary to ascii) is an original / base JS func
	    const base64 = btoa(binary);
	    console.log('Base64 encoding complete, length: ' + base64.length);
	    return base64;
	})();
JS;

    return $jsec;
} // end func
} // end class