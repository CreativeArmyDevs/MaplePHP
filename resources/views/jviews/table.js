
export function tableComponent(data, container, $, builder)
{

    let inst = this;
    if (!data.sort) {
        data.sort = {};
    }

    let out = `
    <table cellpadding="0" cellpadding="0">
        <thead>${thead()}</thead>
        <tbody>${tbody()}</tbody>
    </table>
    `;


    this.eventOnload(() => {
        // Will add click event when template has loaded
        $(".sort").click((e, target) => {
            e.preventDefault();
            let btn = $(target), name = btn.data("name");
            if (typeof name === "string") {
                sort(name);
            }
        });
    });

    /**
     * Render content for the thead
     * @return {string}
     */
    function thead()
    {
        let out = "";
        if (data?.thead?.length > 0) {
            $.each(data.thead, function (k, v) {
                        let key, val;
                if (typeof v === "object") {
                    let keys = Object.keys(v);
                    if (keys.length > 0) {
                        key = keys[0];
                        val = v[key];
                    }
                } else {
                    val = v;
                }
                out += `<th${key ? ' class="sort" data-name="'+key+'"' : ''}>${val}</th>`;
            });
        }
        return out;
    }

    /**
     * Render content for the tbody
     * @return {string}
     */
    function tbody()
    {
        let out = "";
        $.each(data.feed, (k, row) => {
            out += "<tr>";
            out += inst.renderMustache(cells(), row);
            out += "</tr>";
        });
        return out;
    }

    /**
     * Build all the tbody cells
     * @return {string}
     */
    function cells()
    {
        let out = "";
        $.each(data.tbody, (k, val) => {
            out += "<td>";
            if (typeof val === "object") {
                if (val?.type === "tooltip") {
                    out += tooltip(val);
                } else {
                    out += "www";
                }
            } else {
                out += val;
            }
            out += "</td>";
        });
        return out;
    }

    /**
     * Require the tooltip component/view
     * @param  {object} obj Pass data to tooltip
     * @return {string}
     */
    function tooltip(obj)
    {
        let config = {
            position: {
                bottom: true,
                right: true
            }
        };
        $.extend(config, obj);
        return inst.withView("tooltip", config).getResponse();
    }

    /**
     * Sort function to sort object cells
     * @param  {string} name Column name
     * @return {void}
     */
    function sort(name)
    {
        data.sort[name] = (!data.sort[name]) ? 1 : 0;
        if (data?.sort?.[name]) {
            data.feed.sort((a, b) => (b[name] ?? "").localeCompare(a[name] ?? ""));
        } else {
            data.feed.sort((a, b) => (a[name] ?? "").localeCompare(b[name] ?? ""));
        }
        inst.update();
    }


    // Return the output to Startox
    if (!data.feed || data.feed.length <= 0) {
        return `<h1 class="title headline-4 align-center">Kunde inte hitta några resultat</h1>`;
    }
    return out;

}