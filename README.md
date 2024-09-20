# Nginx-Laravel10

## docker
```cmd
docker compose exec php /bin/sh -c "composer intall && chmod -R 777 storage && php artisan key:geneate" 
docker exec -it container /bin/sh -c "composer intall && chmod -R 777 storage && php artisan key:geneate" 
```

## Checkstyle
```cmd
composer require vietstars/code-style

php artisan vendor:publish --tag=config-fixer --force

"scripts": {
  "check-style": "php-cs-fixer fix --dry-run --diff",
  "fix-style": "php-cs-fixer fix"
}
```

## Logs viewer
```cmd
composer require vietstars/logs-viewer
```

## Dev debugger
```cmd
composer require vietstars/dev-debugger
```

## Sactum
```cmd
# build config and migragtion
php artisan vendor:publish --provider=”Laravel\Sanctum\SanctumServiceProvider”
```
## Enable middleware
```php
# http\Kernel.php
# api
\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
```

## Add api token to user model
```php
use Laravel\Sanctum\HasApiTokens; 
```

## Sign-in Flow
![Login flow](./img/email-2fa-login.svg)

## Post relation
![Login flow](./img/post-relation.svg)

## Emarket-4u
![Login flow](./img/emarket-4u.drawio.svg)

## RSA
```php
    use phpseclib3\Crypt\RSA;
 // "phpseclib/phpseclib": "^3.0"

    $this->user->create([
      'name' => $faker->name(),
      'email' => $faker->unique()->safeEmail(),
      'email_verified_at' => now()->format('Y-m-d H:i:s'),
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
      'remember_token' => Str::random(10),
    ]);
    
    $plaintext = "hello world! rsa world";

    $private = RSA::createKey(4096);
    $ciphertext = $private->getPublicKey()->encrypt($plaintext);
    dump('plaintext: ', $private->decrypt($ciphertext));
    echo $private;
    echo $private->getPublicKey();
    
    echo '0x'.strtoupper(md5($private));

    'pri_key' => $private,
    'pub_key' => $private->getPublicKey(),
    'ciphertext' => base64_encode($ciphertext),
```

```js
function ab2str(buf) {
    return String.fromCharCode.apply(null, new Uint8Array(buf));
}

function str2ab(str) {
    // Fix: Don't double the size 
    var buf = new ArrayBuffer(str.length);
    // Fix: Apply a Uint8Array!
    var bufView = new Uint8Array(buf);
    for (var i=0, strLen=str.length; i < strLen; i++) {
        bufView[i] = str.charCodeAt(i);
    }
    return buf;
}

/**
 * [importPrivateKey pkcs8 for private key]
 * @param  {[type]} pem [description]
 * @return {[type]}     [description]
 */
function importPrivateKey(pem) {

    const pemHeader = "-----BEGIN PRIVATE KEY-----\n";
    const pemFooter = "\n-----END PRIVATE KEY-----";
    const pemContents = pem.substring(
        pemHeader.length, 
        pem.length - pemFooter.length
    );

    const binaryDerString = window.atob(pemContents);
    const binaryDer = str2ab(binaryDerString);

    return window.crypto.subtle.importKey(
        "pkcs8",
        binaryDer,
        {
            name: "RSA-OAEP",
            modulusLength: 1024,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: {name: "SHA-256"}
        },
        true,
        ["decrypt"]
    );
}

/**
 * [importPublicKey spki for public key]
 * @param  {[type]} pem [description]
 * @return {[type]}     [description]
 */
function importPublicKey(pem) {

    const pemHeader = "-----BEGIN PUBLIC KEY-----\n";
    const pemFooter = "\n-----END PUBLIC KEY-----";

    const pemContents = pem.substring(
        pemHeader.length, 
        pem.length - pemFooter.length
    );

    const binaryStr = window.atob(pemContents);
    const binaryBuff = str2ab(binaryStr);

    return window.crypto.subtle.importKey(
        "spki",
        binaryBuff,
        {
            name: "RSA-OAEP",
            modulusLength: 1024,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: {name: "SHA-256"}
        },
        true,
        ["encrypt"]
    );
}

async function decryptWithPrivateKey(ciphertext, prikey) {
    var decryptedBuffer = str2ab(window.atob(ciphertext));
    var decrypted = await window.crypto.subtle.decrypt(
        {name:"RSA-OAEP"},
        prikey,
        decryptedBuffer
    );
    return ab2str(decrypted);
}

async function encryptWithPublicKey(data, publicKey) {
  const encodedData = new TextEncoder().encode(data);
  const encryptedData = await window.crypto.subtle.encrypt(
    {
      name: "RSA-OAEP",
    },
    publicKey,
    encodedData
  );
  return window.btoa(String.fromCharCode(...new Uint8Array(encryptedData)));
}

(async function() {

    var privateKey = `{{ $pri_key }}`;
    var publicKey = `{{ $pub_key }}`;
    var ciphertext = `{{ $ciphertext }}`;
    var plain_text = `hello world crypto from js`;

    var prikey = await importPrivateKey(privateKey);
    console.log('key: ', prikey);

    var decryptedText = await decryptWithPrivateKey(ciphertext, prikey);
    console.log('decryptText: ', decryptedText);

    var pubkey = await importPublicKey(publicKey);
    console.log('pub: ', pubkey);

    const encrypted = await encryptWithPublicKey(plain_text, pubkey);

    console.log('encryptText: ', encrypted);

    var decryptedTextencrypted = await decryptWithPrivateKey(encrypted, prikey);
    console.log('decryptedTextencrypted: ', decryptedTextencrypted);
})();

//sign digital
(async () => {
    // Generate Sign digital
    const keyDetails = await window.crypto.subtle.generateKey(
        {
            name: "RSASSA-PKCS1-v1_5",
            modulusLength: 2048,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: {name: "SHA-256"},
        },
        true, 
        ['verify', 'sign'] 
    );

    //Generate symetric key
    const keyDetails = await window.crypto.subtle.generateKey(
        {
            name: "RSA-OAEP",
            modulusLength: 4096,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: {name: "SHA-256"},
        },
        true, 
        ['encrypt', 'decrypt'] 
    );

    console.log('2- key details: ', keyDetails)

    // Export
    const publicKey = await window.crypto.subtle.exportKey('jwk', keyDetails.publicKey);
    const privateKey = await window.crypto.subtle.exportKey('jwk', keyDetails.privateKey);
    console.log('2: pub-key: ', publicKey)
    console.log('2: pri-key: ', privateKey)

    // Adapt parameters and import
    publicKey.key_ops = ['encrypt'];
    privateKey.key_ops = ['decrypt'];

    publicKey.alg = 'RSA-OAEP-256';
    privateKey.alg = 'RSA-OAEP-256';

    const publicKeyReloaded = await window.crypto.subtle.importKey(
        "jwk", 
        publicKey, {name: "RSA-OAEP", hash: {name: "SHA-256"}}, 
        true, 
        ["encrypt"]
    );
    const privateKeyReloaded = await window.crypto.subtle.importKey(
        "jwk", 
        privateKey,{name: "RSA-OAEP", hash: {name: "SHA-256"}}, 
        true, 
        ["decrypt"]
    );
    console.log('2: loaded pub-key: ', publicKeyReloaded)
    console.log('2: loaded pri-key: ', privateKeyReloaded)

    // Encrypt/Decrypt
    const enc = new TextEncoder();
    const encodedText = enc.encode("testing 1234");
    const encryptedText = await window.crypto.subtle.encrypt(
        {name: "RSA-OAEP"}, 
        publicKeyReloaded, 
        encodedText
    );

    console.log('2: encryptedText: ', ab2b64(encryptedText));

    const dec = new TextDecoder();
    const decryptedText = await window.crypto.subtle.decrypt(
        {name: "RSA-OAEP"}, 
        privateKeyReloaded, 
        encryptedText
    );

    console.log('2: decryptedText: ', dec.decode(decryptedText));

    // Helper
    function ab2b64(arrayBuffer) {
        return window.btoa(String.fromCharCode.apply(null, new Uint8Array(arrayBuffer)));
    }
})();
```
