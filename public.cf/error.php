<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	
	$normalized_control = false;
	require($dirPWroot."resource/hpe/init_ps.php");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main { background-image: conic-gradient( from 180deg at 50% 50%, rgba(36, 209, 101, .09) 0deg, rgba(226, 214, 54, .09) 55.3deg, rgba(254, 108, 91, .09) 120deg, rgba(204, 60, 203, .09) 165deg, rgba(159, 51, 253, .09) 213.75deg, rgba(24, 117, 243, .09) 288.12deg, rgba(22, 119, 240, .09) 320deg, rgba(36, 209, 101, .09) 360deg ); }
			main .purple { --btn-bgc: var(--clr-bs-purple); }
			main .error *:not(path) { transition: var(--time-tst-medium); }
			main .error { display: flex; flex-direction: row; justify-content: center; }
			main .error > * { margin: 5px; }
			main .error .image .hexagon path {
				stroke: var(--btn-bgc); stroke-width: 6;
				fill: none;
				animation: float_obj 1s infinite ease-in-out alternate;
				animation-delay: calc(0.2s * var(--i));
			}
			@keyframes float_obj {
				100% { transform: translateY(20px); }
			}
			main .error .info {
				padding-left: 40px;
				display: flex; flex-direction: column; justify-content: space-evenly;
			}
			main .error .info .intel {
				padding: 0px 5px;
				font-size: 2.125rem !important;
			}
			main .error .info .action { display: flex; }
			main .error .info .action > * {
				margin: 0px 5px;
				border-radius: 12.5px;
				font-size: 15px; line-height: 30px;
			}
			@media only screen and (max-width: 768px) {
				main .error { flex-direction: column; justify-content: flex-start; }
				main .error .image {
					height: 375px;
					text-align: right;
				}
				main .error .image svg { transform: scale(0.75) translate(45px, -64.5px); }
				main .error .info { padding-left: 0px; }
				main .error .info .intel { font-size: 1.25rem !important; }
				main .error .info .intel p { line-height: 1.25; }
				main .error .info .action { justify-content: center; }
				main .error .info .action > * { margin: 0px 2.5px; }
			}
		</style>
		<script type="text/javascript">
			function hsc_fx() {
				const http_status_codes = { // HSC REF : iana.org/assignments/http-status-codes/http-status-codes.xhtml
					// Informational
					100: "Continue",
					101: "Switching Protocols",
					102: "Processing",
					103: "Checkpoint",
					// 104-109: "Unassigned",
					110: "Response is Stale",
					111: "Revalidation Failed",
					112: "Disconnected Operation",
					113: "Heuristic Expiration",
					// 104-198: "Unassigned",
					199: "Miscellaneous Warning",
					
					// Success
					200: "OK",
					201: "Created",
					202: "Accepted",
					203: "Non-Authoritative Information",
					204: "No Content",
					205: "Reset Content",
					206: "Partial Content",
					207: "Multi-Status",
					208: "Already Reported",
					// 209-213: "Unassigned",
					214: "Transformation Applied",
					// 215-217: "Unassigned",
					218: "This is fine", // Apache
					// 219-225: "Unassigned",
					226: "IM Used",
					// 227-298: "Unassigned",
					299: "Miscellaneous Persistent Warning",
					
					// Redirect
					300: "Multiple Choices",
					301: "Moved Permanently",
					302: "Found",
					303: "See Other",
					304: "Not Modified",
					305: "Use Proxy",
					306: "Switch Proxy",
					307: "Temporary Redirect",
					308: "Permanent Redirect",
					// 309-399: "Unassigned",
					
					// Client error
					400: "Bad Request",
					401: "Unauthorized",
					402: "Payment Required",
					403: "Forbidden",
					404: "Not Found",
					405: "Method Not Allowed",
					406: "Not Acceptable",
					407: "Proxy Authentication Required",
					408: "Request Timeout",
					409: "Conflict",
					410: "Gone",
					411: "Length Required",
					412: "Precondition Failed",
					413: "Request Entity Too Large",
					414: "Request-URI Too Long",
					415: "Unsupported Media Type",
					416: "Requested Range Not Satisfiable",
					417: "Expectation Failed",
					418: "I'm a teapot",
					419: "Client Error", // Laravel: Page Expired
					420: "Enhance Your Calm", // Twitter // Spring: Method Failure
					421: "Misdirected Request",
					422: "Unprocessable Entity",
					423: "Locked",
					424: "Failed Dependency",
					425: "Unordered Collection",
					426: "Upgrade Required",
					// 427: "Unassigned",
					428: "Precondition Required",
					429: "Too Many Requests",
					430: "Request Header Fields Too Large", // Shopify
					// 431-439: "Unassigned",
					440: "Login Time-out", // IIS
					// 441-443: "Unassigned",
					444: "Connection Closed Without Response", // nginx: No Response
					// 445-448: "Unassigned",
					449: "Retry With", // IIS
					450: "Blocked by Windows Parental Controls", // Microsoft // IIS: Redirect
					451: "Unavailable For Legal Reasons",
					// 452-459: "Unassigned",
					460: "Client closed the connection with the load balancer before the idle timeout period elapsed", // AWS
					// 461-462: "Unassigned",
					463: "The load balancer received an X-Forwarded-For request header with more than 30 IP addresses", // AWS
					// 464-493: "Unassigned",
					494: "Request header too large", // nginx
					495: "SSL Certificate Error", // nginx
					496: "SSL Certificate Required", // nginx
					497: "HTTP Request Sent to HTTPS Port", // nginx
					498: "Invalid Token", // Esri
					499: "Client Closed Request", // nginx
					
					// Server error
					500: "Internal Server Error",
					501: "Not Implemented",
					502: "Bad Gateway",
					503: "Service Unavailable",
					504: "Gateway Timeout",
					505: "HTTP Version Not Supported",
					506: "Variant Also Negotiates",
					507: "Insufficient Storage",
					509: "Bandwidth Limit Exceeded", // Apache
					510: "Not Extended",
					511: "Network Authentication Required",
					// 512-519: "Unassigned",
					520: "Web Server Returned an Unknown Error", // Cloudflare
					521: "Web Server Is Down", // Cloudflare
					522: "Connection Timed Out", // Cloudflare
					523: "Origin Is Unreachable", // Cloudflare
					524: "A Timeout Occurred", // Cloudflare
					525: "SSL Handshake Failed", // Cloudflare
					526: "Invalid SSL certificate", // Cloudflare
					527: "Railgun Error", // Cloudflare
					// 528: "Unassigned",
					529: "Site is overloaded", // Qualys
					530: "Site is frozen", // Pantheon
					// 531-560: "Unassigned",
					561: "Unauthorized", // AWS
					// 562-597: "Unassigned",
					598: "Network read timeout error", // Informal convention
					599: "Network Connect Timeout Error",
					
					// System HSC
					900: "Not found!",
					901: "No permission",
					902: "Wrong!",
					903: "Page under construction",
					904: "JS disabled",
					905: "Server error",
					906: "Safari not supported",

					// Cloudflare errors
					1000: "DNS points to prohibited IP",
					1001: "DNS resolution error", // Unable to resolve
					1002: "DNS points to Prohibited IP",
					1002: "Restricted",
					1003: "Access Denied: Direct IP Access Not Allowed", // Bad Host header
					1004: "Host Not Configured to Serve Web Traffic",
					1006: "Access Denied: Your IP address has been banned", // Quota exceeded. You are currently allowed 5 monitors. Please re-use or delete any unused monitors.
					1007: "Access Denied: Your IP address has been banned",
					1008: "Access Denied: Your IP address has been banned",
					1009: "Access Denied: Country or region banned",
					1010: "The owner of this website has banned your access based on your browser's signature",
					1011: "Access Denied (Hotlinking Denied)",
					1012: "Access Denied",
					1013: "HTTP hostname and TLS SNI hostname mismatch",
					1014: "CNAME Cross-User Banned",
					1015: "You are being rate limited",
					1016: "Origin DNS error",
					1018: "Could not find host", // Unable to resolve because of ownership lookup failure
					1019: "Compute server error",
					1020: "Access denied",
					1023: "Could not find host", // Unable to resolve because of feature lookup failure
					1025: "Please check back later",
					1033: "Argo Tunnel error",
					1035: "Invalid request rewrite (invalid URI path)",
					1036: "Invalid request rewrite (maximum length exceeded)",
					1037: "Invalid rewrite rule (failed to evaluate expression)",
					1040: "Invalid request rewrite (header modification not allowed)",
					1041: "Invalid request rewrite (invalid header value)",
					1101: "Rendering error",
					1102: "Rendering error",
					1104: "A variation of this email address is already taken in our system. Only one variation is allowed.",
					1106: "Access Denied: Your IP address has been banned",
					1200: "Cache connection limit"
				};
				var getError = function() {
					var hsc;
					function setDefaultHSC() {
						hsc = 900;
						history.replaceState(null, null, "/error/"+hsc.toString());
					}
					if (["/error", "/error/"].includes(location.pathname)) setDefaultHSC();
					else {
						hsc = /^\/error\/\d{3,4}$/.test(location.pathname) ? parseInt(location.pathname.split("/").at(-1)) : <?php echo $_GET['hsc'] ?? 0; ?>;
						if (!Object.keys(http_status_codes).includes(hsc.toString())) setDefaultHSC();
					}
					return hsc;
				};
				var setError = function(hsc) {
					hsc = parseInt(hsc);
					var last = {
						code: "Error: " + hsc.toString() + " | " + document.title,
						text: http_status_codes[hsc]
					};
					document.querySelector("main .error .info .intel h2").innerText = hsc.toString();
					document.title = last.code;
					$('head meta[name="twitter:title"], head meta[property="og:title"]').attr("content", last.code);
					document.querySelector("main .error .info .intel p").innerText = last.text;
					$('head meta[name="description"], head meta[property="og:description"]').attr("content", last.code);
				};
				var customError = function(hsc) {
					hsc = parseInt(hsc);
					if (Object.keys(http_status_codes).includes(hsc.toString())) {
						history.pushState(null, null, "/error/"+hsc.toString());
						if (typeof sys.back.start !== "undefined") sys.back.start();
						setError(hsc);
						app.ui.notify(1, [0, "New HTTP status code set ("+hsc.toString()+")"]);
					} else app.ui.notify(1, [1, "There is no HTTP status code "+hsc.toString()]);
				};
				var initialize = function() {
					setError(getError());
				};
				return {
					init: initialize,
					set: customError
				};
			} const hsc = hsc_fx(); delete hsc_fx;
			$(document).ready(function() {
				hsc.init();
			});
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<div class="error">
					<div class="image">
						<svg width="380px" height="500px" viewBox="0 0 837 1045" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g class="hexagon">
								<path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" style="--i: 0;" class="blue" -stroke="#007FB2"></path>
								<path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" style="--i: 1;" class="red" -stroke="#EF4A5B"></path>
								<path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" style="--i: 2;" class="purple" -stroke="#795D9C"></path>
								<path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" style="--i: 3;" class="yellow" -stroke="#F2773F"></path>
								<path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" style="--i: 4;" class="green" -stroke="#36B455"></path>
							</g>
						</svg>
					</div>
					<div class="info">
						<div class="intel">
							<h2></h2>
							<p></p>
						</div>
						<div class="action">
							<a href="/" target="_top" role="button" class="blue hollow">Home</a>
							<button onClick="top.history.back()" class="gray hollow">Back</button>
							<button onClick="top.location.reload()" class="gray hollow">Reload</button>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>