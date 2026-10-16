<?php
// Статьи на английском (по slug из data.php). Подключается из inc/lang/en.php.
return array(
    'edinye-trebovaniya-ikt-ib' => array(
        'title' => 'Unified ICT and Information Security Requirements: what government and quasi-public organisations must do',
        'excerpt' => 'Who the Unified ICT and IS Requirements apply to, what to check in your infrastructure first and how to prepare for an inspection.',
        'cta' => array('Need an audit against the Unified Requirements?', 'We will review your infrastructure and prepare a work plan'),
        'body' => <<<'HTML'
<p>The Unified Requirements for information and communication technologies and information security define how government bodies and quasi-public organisations in Kazakhstan must build, protect and maintain their IT infrastructure.</p>
<h2>Who the requirements apply to</h2>
<p>First of all, government bodies, quasi-public sector entities and owners of critical information and communication infrastructure. In practice they also affect contractors: technical specifications in public tenders often refer to the Unified Requirements directly.</p>
<h2>What to check in your infrastructure</h2>
<ul>
<li>Firewalling and network segmentation</li>
<li>Backup and regular restore testing</li>
<li>Event logging and log retention</li>
<li>Account and access rights management</li>
<li>Timely updates and anti-virus protection</li>
<li>Documents: security policy, procedures, list of protected resources</li>
</ul>
<h3>Where to start</h3>
<p>Start with an audit: compare the current state with the requirements and build a prioritised work plan. Some items are covered by configuring the equipment you already have, others by supplying and implementing security tools.</p>
<table>
<thead><tr><th>Area</th><th>Typical solution</th></tr></thead>
<tbody>
<tr><td>Perimeter protection</td><td>Fortinet NGFW</td></tr>
<tr><td>Backup</td><td>Veeam, Veritas</td></tr>
<tr><td>Vulnerabilities</td><td>Vulnerability scanner</td></tr>
</tbody>
</table>
<p class="post-note">Demo text for the mock-up: check against the current version of the requirements before publishing.</p>
HTML
    ),
    'fortinet-partner-status' => array(
        'title' => 'TEC International confirms its Fortinet partner status',
        'excerpt' => 'Example of a news item about a partner status: short, dated and linked to a solution.',
        'cta' => array('Need a Fortinet NGFW?', 'We will select the model and licences for your network'),
        'body' => <<<'HTML'
<p>Example news format. TEC International has confirmed its Fortinet partner status. For clients this means direct work with the manufacturer, official prices, warranty and vendor support.</p>
<p>The partner certificate is available under “About us → Certificates”.</p>
<p class="post-note">Demo news item: replace the text and status with the actual ones.</p>
HTML
    ),
    'vmware-broadcom' => array(
        'title' => 'VMware after the Broadcom acquisition: what has changed',
        'excerpt' => 'Subscriptions instead of perpetual licences, new product bundles and the alternatives available.',
        'cta' => array('Need to recalculate VMware licences?', 'We will compare the options and calculate the total cost of ownership'),
        'body' => <<<'HTML'
<p>After Broadcom acquired VMware, the licensing model changed significantly, and many organisations are reviewing their virtual infrastructure plans.</p>
<h2>What has changed</h2>
<ul>
<li>Move to subscriptions: perpetual licences are no longer sold</li>
<li>Products are combined into large bundles</li>
<li>Licensing by the number of processor cores</li>
</ul>
<h2>What are the options</h2>
<h3>Stay on VMware</h3>
<p>This makes sense if your infrastructure depends heavily on the ecosystem. It is important to count cores correctly and choose the right bundle, since it is easy to overpay.</p>
<h3>Move to an alternative platform</h3>
<p>Migration needs a pilot and a plan, but it can reduce the total cost of ownership. Backup during the migration is a must.</p>
<p class="post-note">Demo text for the mock-up.</p>
HTML
    ),
    'aeronavigation-svyaz' => array(
        'title' => 'Communication equipment project for air navigation completed',
        'excerpt' => 'Example of a project news item: the task, what was done, the result.',
        'cta' => array('Have a similar task?', 'Tell us about your project and we will offer 2–3 solution options'),
        'body' => <<<'HTML'
<p>Example format of a project news item. Task: upgrade the communication equipment at air navigation sites. What we did: supply, installation and commissioning together with the manufacturer.</p>
<p class="post-note">Demo news item: replace with the actual text; client name only with their consent.</p>
HTML
    ),
    'server-1c-sap' => array(
        'title' => 'A server for 1C and SAP: how to choose the configuration',
        'excerpt' => 'CPU, memory and disks: what to look at so your accounting system does not slow down.',
        'cta' => array('Need help choosing a server?', 'We will offer 2–3 configurations from HPE, Dell or Lenovo'),
        'body' => <<<'HTML'
<p>Business systems load a server in different ways, so the most powerful server is not always the most suitable one.</p>
<h2>Processor</h2>
<p>For 1C, high core frequency matters: many operations run in a single thread. For SAP and large databases, the number of cores matters more.</p>
<h2>Memory and disks</h2>
<ul>
<li>Enough memory to allow for database growth</li>
<li>Fast NVMe drives for the database</li>
<li>A separate array for backups</li>
</ul>
<h2>Fault tolerance</h2>
<p>Redundant power supplies, RAID and a backup plan are the minimum for an accounting system.</p>
<p class="post-note">Demo text for the mock-up.</p>
HTML
    ),
    'goszakupki-it' => array(
        'title' => 'How to buy IT equipment through public procurement',
        'excerpt' => 'What to check in the technical specification to get the right equipment on time.',
        'cta' => array('Preparing a tender?', 'We will help you write a correct technical specification'),
        'body' => <<<'HTML'
<p>Mistakes in the technical specification are the main reason IT tenders fail: the supplier formally meets the terms, but the equipment does not fit.</p>
<h2>What to check in the specification</h2>
<ul>
<li>Specifications that are not tied to a single brand</li>
<li>Compatibility with your current infrastructure</li>
<li>Delivery and installation times</li>
<li>Manufacturer warranty and service</li>
</ul>
<h2>Timing</h2>
<p>Allow time for delivery: for some equipment it takes several months.</p>
<p class="post-note">Demo text for the mock-up.</p>
HTML
    ),
);
