const createRenderer = require("vue-server-renderer").createRenderer;
const { JSDOM, VirtualConsole } = require("jsdom");

process.stdin.setEncoding("utf8");
process.stdout.setEncoding("utf8");
let domInline = "";

process.stdin.on("readable", () =>
{
    // We will see later how Symfony communicates on this input
    let chunk;

    // Use a loop to make sure we read all available data.
    while ((chunk = process.stdin.read()) !== null)
    {
        domInline += chunk;
    }
});

process.stdin.on("end", () =>
{
    const virtualDom = new JSDOM(
        domInline.toString(),
        {
            runScripts: "dangerously"
        }
    );

    global.document = virtualDom.window.document;
    global.window = virtualDom.window;
    global.App = virtualDom.window.App;

    try {
        const createApp = require("../../dist/ceres-server.js").default;
        const vueAppElement = document.getElementById("vue-app");
        const vueApp = createApp({
            template: vueAppElement.outerHTML
        });


        vueAppElement.parentElement.replaceChild(
            document.createComment("vue-ssr-outlet"),
            vueAppElement
        );

        document.getElementById("ssr-script-container").innerHTML = vueAppElement.outerHTML;

        createRenderer({ template: document.documentElement.outerHTML })
            .renderToString(vueApp, (err, renderedHTML) =>
            {
                if(err)
                {
                    console.log(err);
                    return 0;
                }

                process.stdout.write(renderedHTML);
            });
    } catch (e) {
        // console.log(e);
    }
});

function createElementFromHTML(htmlString)
{
    const div = document.createElement("div");

    div.innerHTML = htmlString.trim();

    // Change this to div.childNodes to support multiple top-level nodes
    return div.firstChild;
}
