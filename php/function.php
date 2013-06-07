<?php
require_once('config.php');
require_once('KLogger.php');



function get_extension($file_name){
  $ext = explode('.', $file_name);
  $ext = (count($ext) == 1 ? null : array_pop($ext));
  return strtolower($ext);
}

function parseXml($filename) {
	$log = new KLogger('../log', KLogger::DEBUG); # Specify the log directory
	$log->logInfo("fichier a parser = " . $filename);
	$document_xml = new DomDocument();
	$document_xml->load($filename);
	$elements = $document_xml->getElementsByTagName('LXFML');
	
	$tree = $elements->item(0); // On récupère le noeud LXFML
	
	$s = array();
	$s = parseChilds($tree, $s);

	return $s;
}

function parseChilds($node, $s) {
	$childs = $node->childNodes;
	
	foreach($childs as $child) {
		if($child->hasChildNodes() == true) {
			$s = parseChilds($child, $s);
		} else {
			$s = parseNode($node, $s);
		}
	}
	return $s;
}


function parseNode($node, $s) {
	$nom = $node->nodeName;
	
	$log = new KLogger('../log', KLogger::DEBUG); # Specify the log directory
	
	
	if ($nom == 'Part') {
		$designId = $node->attributes->getNamedItem('designID')->nodeValue;
		$material = $node->attributes->getNamedItem('materials')->nodeValue;
		
		if (isset($s[$designId][$material])) {
			$s[$designId][$material] = $s[$designId][$material] + 1;
		} else {
			$s[$designId][$material] = 1;
		}
		
		$log->logInfo("nom : " . $nom);
		$log->logInfo("\tdesignId : " . $designId);
		$log->logInfo("\tmaterial : " . $material);
		//$log->logInfo("\tqty : " . $s[$p]);
	}
	return $s;
}

$tabColor = array(
1	=>	1	,
2	=>	9	,
3	=>	33	,
5	=>	2	,
6	=>	38	,
9	=>	23	,
11	=>	72	,
12	=>	29	,
18	=>	28	,
20	=>	60	,
21	=>	5	,
22	=>	100022	,
23	=>	7	,
24	=>	3	,
25	=>	8	,
26	=>	11	,
27	=>	10	,
28	=>	6	,
29	=>	37	,
36	=>	96	,
37	=>	36	,
38	=>	68	,
39	=>	44	,
40	=>	12	,
41	=>	17	,
42	=>	15	,
43	=>	14	,
44	=>	19	,
45	=>	62	,
47	=>	18	,
48	=>	20	,
49	=>	16	,
50	=>	46	,
100	=>	26	,
101	=>	25	,
102	=>	42	,
103	=>	49	,
104	=>	24	,
105	=>	31	,
106	=>	4	,
107	=>	39	,
108	=>	100108	,
109	=>	100109	,
110	=>	43	,
111	=>	13	,
112	=>	73	,
113	=>	50	,
114	=>	100	,
115	=>	76	,
116	=>	40	,
117	=>	101	,
118	=>	41	,
119	=>	34	,
120	=>	35	,
121	=>	32	,
123	=>	100123	,
124	=>	71	,
125	=>	100125	,
126	=>	51	,
127	=>	61	,
128	=>	100128	,
129	=>	102	,
131	=>	66	,
132	=>	111	,
133	=>	100133	,
134	=>	100134	,
135	=>	55	,
136	=>	54	,
137	=>	100137	,
138	=>	69	,
139	=>	84	,
140	=>	63	,
141	=>	80	,
143	=>	74	,
145	=>	78	,
146	=>	100146	,
147	=>	81	,
148	=>	77	,
149	=>	100149	,
150	=>	119	,
151	=>	48	,
153	=>	58	,
154	=>	59	,
157	=>	121	,
158	=>	100158	,
168	=>	100168	,
176	=>	100176	,
178	=>	100178	,
179	=>	95	,
180	=>	100180	,
182	=>	98	,
183	=>	83	,
184	=>	100184	,
185	=>	100185	,
186	=>	100186	,
187	=>	100187	,
188	=>	100188	,
189	=>	100189	,
190	=>	100190	,
191	=>	110	,
192	=>	88	,
193	=>	100193	,
194	=>	86	,
195	=>	97	,
196	=>	109	,
197	=>	100197	,
198	=>	93	,
199	=>	85	,
200	=>	70	,
208	=>	99	,
209	=>	100209	,
210	=>	100210	,
211	=>	100211	,
212	=>	105	,
213	=>	100213	,
216	=>	100216	,
217	=>	91	,
218	=>	100218	,
219	=>	100219	,
220	=>	100220	,
221	=>	47	,
222	=>	104	,
223	=>	100223	,
224	=>	100224	,
225	=>	100225	,
226	=>	103	,
227	=>	100227	,
229	=>	113	,
230	=>	107	,
231	=>	100231	,
232	=>	87	,
233	=>	100233	,
234	=>	100234	,
236	=>	114	,
268	=>	89	,
269	=>	100269	,
283	=>	90	,
284	=>	100284	,
294	=>	118	,
296	=>	100296	,
297	=>	115	,
298	=>	67	,
299	=>	65	,
301	=>	22	,
304	=>	117	,
306	=>	116	,
308	=>	120	,
309	=>	100309	,
310	=>	21	,
311	=>	108	,
312	=>	150	,
315	=>	100315	,
316	=>	100316	,
321	=>	153	,
322	=>	156	,
323	=>	152	,
324	=>	157	,
325	=>	154	,
326	=>	158	,
329	=>	159	,
330	=>	155	
);
?>