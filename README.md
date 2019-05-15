# Time-Based-Random-UUID

🆔 Mix of a time-based and a randomly generated UUID - Mix of v1 and v4

## Information

This is an alternative version of a randomly generated UUID, using both the timestamp and pseudo-random bytes.

Maybe I'm wrong about all that but I thought mixing the UUID v1 and v4 methods would make the generated UUID more secure, as it ensures a zero-chance of collision of a single computer and a -really- low chance of collision on multiple computers (because of both probability and time).

The UUID contains 62 randomly generated bits, so there is 2^62 different outcomes for that part of the generated UUID, which means you'd start geeting colisions after about 2^31 generations. That being said, that would only happen if you were able to generate that many UUIDs within 100 nanosecond to 1 microsecond (because the gettimeofday() function used here returns a microsecond-precise timestamp).

This generated UUID format is `oooooooo-oooo-Mooo-Nxxx-xxxxxxxxxxxx`, it concatenates:
- `o`: The current timestamp (60 bits) (time_low, time_mid, time_high)
- `M`: The version (4 bits)
- `N`: The variant (2 bits)
- `x`: Pseudo-random values (62 bits)

Based on:
- Code from an UUID v1 Generation script: [GitHub @ fredriklindberg / class.uuid.php](https://github.com/fredriklindberg/class.uuid.php/blob/c1de11110970c6df4f5d7743a11727851c7e5b5a/class.uuid.php#L220)
- Code from an UUID v4 Generation script: [Answer to "PHP function to generate v4 UUID" on StackOverflow](https://stackoverflow.com/a/15875555/5255556)
