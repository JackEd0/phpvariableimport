// The module 'vscode' contains the VS Code extensibility API
// Import the module and reference it with the alias vscode in your code below
import * as vscode from 'vscode';

// This method is called when your extension is activated
// Your extension is activated the very first time the command is executed
export function activate(context: vscode.ExtensionContext) {

	// Use the console to output diagnostic information (console.log) and errors (console.error)
	// This line of code will only be executed once when your extension is activated
	console.log('Congratulations, your extension "customimport" is now active!');

	// The command has been defined in the package.json file
	// Now provide the implementation of the command with registerCommand
	// The commandId parameter must match the command field in package.json
  let disposable = vscode.commands.registerCommand('customimport.insertPHPComment', () => {
		// The code you place here will be executed every time your command is executed
		// Display a message box to the user
		// vscode.window.showInformationMessage('Hello World from customimport!');

    // Get the active text editor.
    const editor = vscode.window.activeTextEditor;

    if (editor) {
      // // Define your custom PHP comment here.
      // const phpComment = "/** @var \\Equipments\\Core $Equipments */\n";

      // // Insert the comment at the beginning of the file.
      // editor.edit(editBuilder => {
      //   const position = new vscode.Position(1, 0);
      //   editBuilder.insert(position, phpComment);
      // });

      // vscode.window.showInputBox({ prompt: 'Enter variable name' }).then((variableName) => {
      //   if (variableName) {
      //     // Create the PHP comment using the custom template.
      //     const phpComment = `/** @var \\${variableName}\\Core $${variableName} */\n`;

      //     // Insert the comment at the beginning of the file.
      //     editor.edit((editBuilder) => {
      //       const position = new vscode.Position(0, 0);
      //       editBuilder.insert(position, phpComment);
      //     });
      //   }
      // });

      const selection = editor.selection;
      const variableName = editor.document.getText(selection);

      if (variableName) {
        // remove the $ sign
        const variableNameWithoutSign = variableName.replace('$', '');

        // Create the PHP comment using the custom template.
        let phpComment = `/** @var \\${variableNameWithoutSign}\\Core ${variableName} */\n`;

        // if the first character of the variable name is a lowercase letter, then change it to uppercase
        if (variableNameWithoutSign.charAt(0) === variableNameWithoutSign.charAt(0).toLowerCase()) {
          const firstLetter = variableNameWithoutSign.charAt(0).toUpperCase();
          const restOfTheName = variableNameWithoutSign.slice(1);
          phpComment = `/** @var \\${firstLetter}${restOfTheName}s\\${firstLetter}${restOfTheName} ${variableName} */\n`;
        }

        // Insert the comment at the beginning of the file.
        editor.edit((editBuilder) => {
          const position = new vscode.Position(1, 0);
          editBuilder.insert(position, phpComment);
        });
      }
    }
	});

	context.subscriptions.push(disposable);

  // Add a context menu item to trigger the command when right-clicking on selected text.
  vscode.commands.executeCommand(
    'setContext',
    'variableSelected',
    false
  );

  vscode.window.onDidChangeTextEditorSelection((e) => {
    if (e.textEditor.document.languageId === 'php') {
      const editor = vscode.window.activeTextEditor;
      if (editor) {
        const selection = editor.selection;
        const variableName = editor.document.getText(selection);

        if (variableName) {
          vscode.commands.executeCommand(
            'setContext',
            'variableSelected',
            true
          );
        } else {
          vscode.commands.executeCommand(
            'setContext',
            'variableSelected',
            false
          );
        }
      }
    }
  });

  vscode.commands.registerCommand('customimport.insertPHPCommentContextMenu', () => {
    vscode.commands.executeCommand('customimport.insertPHPComment');
  });

  // vscode.window.createTreeView('variablesView', {
  //   treeDataProvider: new VariablesProvider(),
  // });

  // Create a menu item in the context menu.
  const contextMenuItem = vscode.commands.registerCommand('customimport.insertPHPCommentContextMenu', () => {
    vscode.commands.executeCommand('customimport.insertPHPComment');
  });

  context.subscriptions.push(contextMenuItem);
}

// This method is called when your extension is deactivated
export function deactivate() {}
