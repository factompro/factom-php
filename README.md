# PHP client for Factom® PRO API
This is a PHP client for <a href="https://factom.pro" target="_blank">Factom® PRO</a> API.<br />
API documentation: <a href="https://docs.factom.pro" target="_blank">https://docs.factom.pro</a>

## Usage

### 1. Require library
```php
require_once("FactomAPI.php");
```

### 2. Initialize client
```php
$api_key = "YOUR_API_KEY";
$factom = new FactomAPI($api_key);
```

### 3. Use client to work with Factom® PRO API
1. Get API info
```php
// Get API version
$version = $factom->getAPIInfo();
```

2. Get user info
```php
// Get user info
$user = $factom->getUser();
```

3. Create a chain
```php
// Creates chain on the Factom blockchain
$extIds[0] = "My new chain";
$extIds[1] = "Second ExtID";
$content = "Content of the first entry"; // optional
$chain = $factom->createChain($extIds, $content);
```

4. Get chains
```php
// Get all user’s chains
$chains = $factom->getChains();

// Get user's chains from 41th to 60th
$chains = $factom->getChains(40, 20);

// Get user's chains with status "queue"
// start=0, limit=0 — use defaults pagination params
// status="queue" — filter chains by status "queue" (also "processing" | "completed")
$chains = $factom->getChains(0, 0, "queue");

// Get user's chains in reverse sorting (from oldest to newest)
// start=0, limit=0 — use defaults pagination params
// status=NULL — not filter by status
// sort="asc" — sort results by createdAt ASC ("desc" is default sorting)
$chains = $factom->getChains(0, 0, NULL, "asc");

// Combine all filters and params
// start=40, limit=20, status="queue", sort="asc"
$chains = $factom->getChains(40, 20, "queue", "asc");
```

5. Get chain
```php
// Get Factom chain by Chain ID
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
$chain = $factom->getChain($chainId);
```

6. Get chain entries
```php
// Get entries of Factom chain
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
$entries = $factom->getChainEntries($chainId);

// Filters and params may be applied to results
// Example: start=40, limit=20, status="queue", sort="asc"
$entries = $factom->getChainEntries($chainId, 40, 20, "queue", "asc");
```

7. Get first/last entry of the chain
```php
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";

// Get first entry of Factom chain
$firstEntry = $factom->getChainFirstEntry($chainId);

// Get last entry of Factom chain
$lastEntry = $factom->getChainLastEntry($chainId);
```

8. Search user's chains by external ids
```php
// Search for chains with tag "Forum Thread"
$extIds[0] = "Forum Thread";
$chains = $factom->searchChains($extIds);

// Search for entries with 2 tags simultaneously 
$extIds[1] = "v1.0.0";
$chains2 = $factom->searchChains($extIds);

// Filters and params may be applied to results
// Example: start=40, limit=20, status="completed", sort="asc"
$chains = $factom->searchChains($extIds, 40, 20, "completed", "asc");
```

9. Search chain entries by external ids
```php
// Search entries into Factom chain by external id(s)
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
// Search for entries with tag "Forum Post"
$extIds[0] = "Forum Post";
$entries = $factom->searchChainEntries($chainId, $extIds);

// Search for entries with 2 tags simultaneously 
$extIds[1] = "v1.0.0";
$entries2 = $factom->searchChainEntries($chainId, $extIds);

// Filters and params may be applied to results
// Example: start=40, limit=20, status="processing", sort="asc"
$entries = $factom->searchChainEntries($chainId, $extIds, 40, 20, "processing", "asc");
```

10. Create an entry
```php
// Create entry in the Factom chain
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
$extIds[0] = "My new entry";
$extIds[1] = "Second ExtID";
$content = "Content of the new entry";
$entry = $factom->createEntry($chainId, $extIds, $content);
```

11. Get entry
```php
// Get Factom entry by EntryHash
$entryHash = "dc2160b99b5f46f156e54bdebc81aef3243884b68b2c0c05e4741910738273f2";
$entry = $factom->getEntry($entryHash);
```
