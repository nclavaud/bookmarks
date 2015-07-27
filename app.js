var Page = React.createClass({
    getInitialState: function() {
        return {display: 'table'};
    },
    changeDisplay: function(display) {
        this.setState({display: display});
    },
    render: function() {
        return (
            <div className="container">
                <ul className="nav nav-pills">
                    <li role="display" onClick={this.changeDisplay.bind(this, 'table')} className={'table' == this.state.display ? 'active' : ''}><a href="#">Table</a></li>
                    <li role="display" onClick={this.changeDisplay.bind(this, 'blocks')} className={'blocks' == this.state.display ? 'active' : ''}><a href="#">Blocks</a></li>
                </ul>
                <CoverList display={this.state.display} />
            </div>
        );
    }
});

var CoverList = React.createClass({
    resources: function() {
        return [
            {
                source: "bandcamp",
                url: "https://strandflat.bandcamp.com/track/ten-years-from-the-second",
                title: "Ten years from the second, by Have The Moskovik",
                image: "https://f1.bcbits.com/img/a3801425696_5.jpg",
                player: "https://bandcamp.com/EmbeddedPlayer/v=2/track=694908547/size=large/linkcol=0084B4/notracklist=true/twittercard=true/"
            },
            {
                source: "soundcloud",
                url: "https://soundcloud.com/havethemoskovik/what-a-wonderful-place-this-earth-would-be",
                title: "What A Wonderful Place This Earth Would Be",
                image: "https://i1.sndcdn.com/artworks-000080845250-7wvy52-t200x200.jpg",
                player: "https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F151837891&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter",
            }
        ];
    },
    render: function() {
        if ('blocks' == this.props.display) {
            var covers = this.resources().map(function (cover) {
                return <Cover source={cover.source} title={cover.title} image={cover.image} url={cover.url} />
            });

            return (
                <div>{covers}</div>
            );
        }

        var rows = this.resources().map(function (cover) {
            return <CoverAsTableRow source={cover.source} title={cover.title} image={cover.image} url={cover.url} />
        });

        return (
            <table className="table table-condensed table-striped">{rows}</table>
        );
    }
});

var Cover = React.createClass({
    render: function() {
        var style = {
            backgroundImage: 'url(' + this.props.image + ')'
        };
        return (
            <a href={this.props.url}>
                <div className="cover" style={style}>
                    <div className="title">{this.props.title}</div>
                    <div className="source">{this.props.source}</div>
                </div>
            </a>
        );
    }
});

var CoverAsTableRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>
                    <a href={this.props.url}>{this.props.title}</a>
                </td>
            </tr>
        );
    }
});

React.render(
    <Page />,
    document.getElementById('a')
);
