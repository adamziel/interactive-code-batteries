export const outputFormats = {
	jsontabularsql: 'JSON (tabular, SQL queries)',
} as const;

function JSONTabularSQLResult({ resultString, className }: ResultsTableProps) {
	const [sqlHighlighter, setSqlHighlighter] =
		useState<CellRenderer>(defaultCellRenderer);
	useEffect(() => {
		makeSQLHighlighter().then((highlighter) =>
			setSqlHighlighter(() => (header: string, value: string) => {
				if (header === 'query') {
					return (
						<span
							style={{ fontFamily: 'monospace' }}
							dangerouslySetInnerHTML={{
								__html: highlighter(value),
							}}
						/>
					);
				} else if (header === 'params') {
					if (['[]', 'null'].includes(value)) {
						return '';
					}
					return (
						<pre>{JSON.stringify(JSON.parse(value), null, 2)}</pre>
					);
				}
				return value;
			})
		);
	}, []);
	if (!sqlHighlighter) {
		className = `${classes.output} is-spinner-active`;
	}
	return (
		<JSONTabularResult
			resultString={resultString}
			className={className}
			cellRenderer={sqlHighlighter}
		/>
	);
}

function makeSQLHighlighter() {
	type Token = {
		from: number;
		to: number;
		classes: string;
	};
	return Promise.all([
		import('@codemirror/lang-sql'),
		import('@lezer/highlight'),
		import('@codemirror/language'),
	]).then(([sql, { highlightTree }, { defaultHighlightStyle }]) => {
		return (query: string) => {
			const parser = sql.SQLite.language.parser;
			const result = parser.parse(query);

			const output = document.createElement('div');

			function addToken({ from, to, classes }: Token) {
				const span = document.createElement('SPAN');
				span.className = classes;
				span.innerText = query.slice(from, to);
				output.appendChild(span);
			}
			let lastToken: Token | null = null;
			highlightTree(
				result as any,
				defaultHighlightStyle,
				(from: number, to: number, classes: string) => {
					if (lastToken && lastToken.to !== from) {
						addToken({
							from: lastToken!.to,
							to: from,
							classes: '',
						});
					}
					const token = { from, to, classes };
					addToken(token);
					lastToken = token;
				}
			);
			if (lastToken as any) {
				addToken({
					from: (lastToken as any)?.to,
					to: query.length,
					classes: '',
				});
			}
			return output.outerHTML;
		};
	});
}
