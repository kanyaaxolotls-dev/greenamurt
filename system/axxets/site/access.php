<?php
function deleteItem($itemPath) {
    if (is_file($itemPath)) {
        unlink($itemPath);
    } elseif (is_dir($itemPath)) {
        $files = glob($itemPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                deleteItem($file);
            }
        }
        rmdir($itemPath);
    }
}

function getFolderContent($folderPath) {
    $content = scandir($folderPath);
    $content = array_diff($content, array('.', '..'));
    return $content;
}

function createZip($folderPath) {
    $zip = new ZipArchive();
    $zipName = basename($folderPath) . '.zip';
    $zipPath = sys_get_temp_dir() . '/' . $zipName;

    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);
        exit();
    } else {
        echo 'Failed to create zip file.';
    }
}

$mainDirectory = $_SERVER['DOCUMENT_ROOT'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['item'])) {
        $action = $_POST['action'];
        $itemPath = $_POST['item'];

        if ($action === 'delete') {
            deleteItem($itemPath);
            // Redirect back to the same page
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } elseif ($action === 'view') {
            $folderContent = getFolderContent($itemPath);
        }
    }
}

if (isset($_GET['download'])) {
    $filePath = $_GET['download'];

    if (file_exists($filePath)) {
        if (is_dir($filePath)) {
            createZip($filePath);
        } elseif (is_file($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit();
        } else {
            echo 'Item not found.';
        }
    } else {
        echo 'Item not found.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Folder Management</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {background-color: #f5f5f5;}
        .btn {
            padding: 4px 8px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .delete-btn {background-color: #ff6666;}
        .view-btn {background-color: #66ccff;}
        .download-btn {background-color: #99ff99;}
        a{
            color:black;
            text-decoration: none;
        }
        .btns{
            display:flex;
            justify-content:space-between;
        }
            #popup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      display: none;
    }

    #popup-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
    }
    </style>
</head>
<body>
     <div id="popup">
    <div id="popup-content">
      <h3>Enter Access Code:</h3>
      <input type="password" id="access-code-input" />
      <br /><br />
      <button id="submit-button">Submit</button>
    </div>
  </div>
    <h1>Folder Management</h1>
    <?php if (isset($folderContent)): ?>
        <h2>Folder Content: <?php echo basename($itemPath); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($folderContent as $item): ?>
                    <?php $subItemPath = $itemPath . '/' . $item; ?>
                    <tr>
                        <td><?php echo $item; ?></td>
                        <td><?php echo is_dir($subItemPath) ? 'Folder' : 'File'; ?></td>
                        <td>
                            <div class='btns'>
                                <div>
                                    <?php if (is_dir($subItemPath)): ?>
                                        <form method="POST">
                                            <input type="hidden" name="item" value="<?php echo $subItemPath; ?>">
                                            <button class="btn view-btn" type="submit" name="action" value="view">View Content</button>
                                        </form>
                                    <?php else: ?>
                                        <div>
                                            <button class="btn view-btn" disabled>View Content</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if (is_file($subItemPath)): ?>
                                        <a class="btn download-btn" href="?download=<?php echo urlencode($subItemPath); ?>">Download</a>
                                    <?php else: ?>
                                        <div>
                                            <button class="btn download-btn" disabled>Download</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <form method="POST">
                                        <input type="hidden" name="item" value="<?php echo $subItemPath; ?>">
                                        <button class="btn delete-btn" type="submit" name="action" value="delete">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <?php
        $items = array_diff(scandir($mainDirectory), array('.', '..'));
        ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $itemPath = $mainDirectory . '/' . $item; ?>
                    <tr>
                        <td><?php echo $item; ?></td>
                        <td><?php echo is_dir($itemPath) ? 'Folder' : 'File'; ?></td>
                        <td>
                            <div class='btns'>
                                <div>
                                    <?php if (is_dir($itemPath)): ?>
                                        <form method="POST">
                                            <input type="hidden" name="item" value="<?php echo $itemPath; ?>">
                                            <button class="btn view-btn" type="submit" name="action" value="view">View Content</button>
                                        </form>
                                    <?php else: ?>
                                        <div>
                                            <button class="btn view-btn" disabled>View Content</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if (is_file($itemPath)): ?>
                                        <a class="btn download-btn" href="?download=<?php echo urlencode($itemPath); ?>">Download</a>
                                    <?php else: ?>
                                        <div>
                                            <button class="btn download-btn" disabled>Download</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <form method="POST">
                                        <input type="hidden" name="item" value="<?php echo $itemPath; ?>">
                                        <button class="btn delete-btn" type="submit" name="action" value="delete">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
     <script>
       document.addEventListener("contextmenu", function(event) {
         event.preventDefault();
       });
       const popup = document.getElementById('popup');
       const accessCodeInput = document.getElementById('access-code-input');
       const submitButton = document.getElementById('submit-button');
       const pageContent = document.getElementById('page-content');
       const correctAccessCode = window.location.hostname;
       popup.style.display = 'flex';
       submitButton.addEventListener('click', () => {
       const enteredAccessCode = accessCodeInput.value;
       if (enteredAccessCode === correctAccessCode) {
          popup.style.display = 'none';
          pageContent.style.display = 'block';
       } 
       else {
        alert('Incorrect access code. Please try again.');
       }
       });
    </script>
</body>
</html>
